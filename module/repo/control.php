<?php
declare(strict_types=1);
/**
 * The control file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang, Jinyong Zhu
 * @package     repo
 * @link        https://www.zentao.net
 * @property    repoModel $repo
 * @property    repoZen   $repoZen
 */
class repo extends control
{
    /**
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->scm = $this->app->loadClass('scm');

        $disFuncs = str_replace(' ', '', ini_get('disable_functions'));
        if(stripos(",$disFuncs,", ',exec,') !== false or stripos(",$disFuncs,", ',shell_exec,') !== false)
        {
            return $this->sendError($this->lang->repo->error->useless, true);
        }

        $this->projectID = $this->session->project ? $this->session->project : 0;

        /* Unlock session for wait to get data of repo. */
    }

    /**
     * Common actions.
     *
     * @param  int    $repoID
     * @param  int    $objectID     projectID|executionID
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function commonAction(int $repoID = 0, int $objectID = 0, int $spaceID = 0)
    {
        $serverHeath = $this->loadModel('gitfox')->checkHealth();
        if(!$serverHeath) return $this->locate($this->createLink('gitfox', "devopsIntroduction"));

        $fromModal = in_array($this->app->rawModule, array('git', 'svn'));
        $tab       = $fromModal ? '' :$this->app->tab;
        $this->repos = $this->repo->getRepoPairs($tab, $objectID);

        if($tab == 'project')
        {
            $projects = $this->loadModel('project')->getPairsByProgram();
            $objectID = $this->project->checkAccess($objectID, $projects);
            $project  = $this->project->getByID($objectID);
            if($project && $project->model === 'kanban') return $this->locate($this->createLink('project', 'index', "projectID=$objectID"));

            $this->loadModel('project')->setMenu($objectID);
            $this->view->projectID = $objectID;
        }
        elseif($tab == 'execution')
        {
            $executions = $this->loadModel('execution')->getPairs(0, 'all', "nocode,noprefix,multiple");
            $objectID   = $this->execution->checkAccess($objectID, $executions);
            $execution  = $this->execution->getByID($objectID);
            if($execution && $execution->type === 'kanban') return $this->locate($this->createLink('execution', 'kanban', "executionID=$objectID"));

            if($execution)
            {
                $features = $this->execution->getExecutionFeatures($execution);
                if(!$features['devops']) return print($this->locate($this->createLink('execution', 'task', "executionID=$objectID")));
            }

            $this->loadModel('execution')->setMenu($objectID);
            $this->view->executionID = $objectID;
        }
        elseif($tab != 'admin' && !$fromModal)
        {
            $this->repo->setMenu($this->repos, $repoID, $spaceID);
        }

        if(empty($this->repos) && !in_array(strtolower($this->methodName), array('create', 'edit', 'setrules', 'createrepo', 'import', 'maintain')))
        {
            $method = $this->app->tab == 'devops' ? 'maintain' : 'createRepo';
            return $this->locate(inLink($method, "objectID=$objectID"));
        }
        $this->view->fromModal = $fromModal;
    }

    /**
     * 版本库列表。
     * List all repo.
     *
     * @param  int    $inSpace
     * @param  int    $objectID
     * @param  int    $space
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $type
     * @param  int    $param
     * @access public
     * @return void
     */
    public function maintain(int $inSpace = 0, int $space = 0, int $objectID = 0, string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1, string $type = '', int $param = 0)
    {
        $serverHeath = $this->loadModel('gitfox')->checkHealth();
        if(!$serverHeath) return $this->locate($this->createLink('gitfox', "devopsIntroduction"));

        $repoID = $this->repo->saveState(0, $objectID);
        if($this->viewType !== 'json') $this->commonAction($repoID, $objectID, $inSpace ? $space : 0);

        $repoList = $this->repo->getList(0, $space, $orderBy, null, false, true, $type, $param);
        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal = count($repoList);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $repoList = array_chunk($repoList, $pager->recPerPage);

        if($repoList && !isset($repoList[$pageID - 1])) $pageID = 1;
        $repoList = empty($repoList) ? array() : $repoList[$pageID - 1];

        $products = $this->loadModel('product')->getPairs('all', 0, '', 'all');

        $this->repoZen->buildRepoSearchForm($inSpace, $space, $products, $objectID, $orderBy, $recPerPage, $pageID, $param);

        $this->view->title    = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->browse;
        $this->view->type     = $type;
        $this->view->orderBy  = $orderBy;
        $this->view->objectID = $objectID;
        $this->view->pager    = $pager;
        $this->view->repoList = $repoList;
        $this->view->products = $products;
        $this->view->spaceID  = $space;
        $this->view->spaces   = $this->loadModel('space')->getPairs($this->app->user->admin ? '' : $this->app->user->account);
        $this->view->inSpace  = $inSpace;

        $this->display();
    }

    /**
     * 创建版本库（关联代码库）。
     * Create a repo(Associate with an existing repo).
     *
     * @param  int    $objectID  projectID|executionID
     * @access public
     * @return void
     */
    public function create(int $objectID = 0)
    {
        if($_POST)
        {
            /* Prepare data. */
            $formData         = form::data($this->config->repo->form->create);
            $isPipelineServer = in_array(strtolower($this->post->SCM), $this->config->repo->gitServiceList);
            $repo             = $this->repoZen->prepareCreate($formData, $isPipelineServer);

            /* Create a repo. */
            if($repo) $repoID = $this->repo->create($repo, $isPipelineServer);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(in_array($this->post->SCM, $this->config->repo->notSyncSCM))
            {
                /* Add webhook. */
                $repo = $this->repo->getByID($repoID);
                $this->loadModel($this->post->SCM)->updateCodePath($repo->serviceHost, (int)$repo->serviceProject, (int)$repo->id);
                $this->repo->updateCommitDate($repoID);
            }

            $this->loadModel('action')->create('repo', $repoID, 'created');

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $repoID));
            $link = $this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID", '', false);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link, 'callback' => "importJob($repoID)"));
        }

        $this->commonAction(0, $objectID);
        $this->repoZen->buildCreateForm($objectID);

        $this->display();
    }

    /**
     * 创建版本库，同步创建远程版本库。
     * Create a repo.
     *
     * @param  int    $objectID  projectID|executionID
     * @access public
     * @return void
     */
    public function createRepo(int $objectID = 0, int $spaceID = 0)
    {
        if($_POST)
        {
            if($objectID && (!$this->post->product || empty(array_filter($this->post->product)))) $this->sendError($this->lang->repo->hasNoProduct);
            /* Prepare data. */
            $formData = form::data($this->config->repo->form->createRepo)->get();
            if($formData->acl == 'private' && empty($formData->members))
            {
                $this->sendError(array('members' => sprintf($this->lang->error->notempty, $this->lang->repo->members)));
            }

            /* Create a repo. */
            $repoID = $this->repo->createRepo($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('repo', $repoID, 'created');

            $link = $this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
        }

        $this->commonAction(0, $objectID);
        $this->repoZen->buildCreateRepoForm($objectID);
        $this->view->inSpace = !empty($spaceID);
        $this->view->spaceID = $spaceID;

        $this->display();
    }

    /**
     * 根据任务和执行创建分支。
     * Create a branch by task and execution.
     *
     * @param  int    $objectID
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function createBranch(int $objectID, int $repoID = 0)
    {
        $objectType = $this->app->rawModule;
        if($objectType == 'repo') return $this->createRepoBranch($objectID, $repoID);

        $object     = $this->loadModel($objectType)->fetchByID($objectID);
        $productIds = array(zget($object, 'product', 0));
        if($objectType == 'task') $productIds = $this->loadModel('product')->getProductIDByProject($object->execution, false);

        $repoList  = $this->repo->getListByPriv('haspriv');
        $repoPairs = array();
        foreach($repoList as $repo)
        {
            $linkedProducts = explode(',', $repo->product);
            foreach($productIds as $productID)
            {
                if(in_array($productID, $linkedProducts)) $repoPairs[$repo->id] = $repo->name;
            }
        }
        if(!$repoPairs) return $this->send(array('result' => 'fail', 'message' => $this->lang->repo->error->noFound));

        if(!empty($_POST)) $repoID = (int)$this->post->codeRepo;
        if(!$repoID || !isset($repoPairs[$repoID])) $repoID = key($repoPairs);

        $this->scm->setEngine($repoList[$repoID]);
        if(!empty($_POST))
        {
            $branch = form::data($this->config->repo->form->createBranch)->get();
            $this->scm->createBranch($branch->branchName, $branch->branchFrom);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->repo->saveRelation($repoID, $branch->branchName, $objectID, $objectType);
            $this->loadModel('action')->create($objectType, $objectID, 'createRepoBranch', '', $branch->branchName);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => array('name' => 'loadModal', array($this->createLink($objectType, 'createBranch', "objectID={$objectID}")))));
        }

        $canCreate = $object->status == 'active';
        if($objectType == 'task') $canCreate = $object->status == 'wait' || $object->status == 'doing';
        $this->view->linkedBranches = $this->repo->getLinkedBranch($objectID, $objectType);
        $this->view->repoPairs      = $repoPairs;
        $this->view->allRepos       = $this->repo->getRepoPairs('repo', 0, false);
        $this->view->repoID         = $repoID;
        $this->view->objectID       = $objectID;
        $this->view->fromList       = $this->repoZen->getBranchAndTagOptions($this->scm);
        $this->view->objectType     = $objectType;
        $this->view->canCreate      = $canCreate;
        $this->display();
    }

    /**
     * 取消代码分支的关联。
     * Unlink code branch.
     *
     * @access public
     * @return void
     */
    public function unlinkBranch()
    {
        $objectType = $this->app->rawModule;
        $branch     = (string)$this->post->branch;
        $objectID   = (int)$this->post->objectID;
        $repoID     = (int)$this->post->repoID;
        $this->repo->unlinkObjectBranch($objectID, $objectType, $repoID, $branch);
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->loadModel('action')->create($objectType, $objectID, 'unlinkRepoBranch', '', $branch);
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => 'loadModal("' . $this->createLink($objectType, 'createBranch', "objectID={$objectID}") . '")'));
    }

    /**
     * Edit a repo.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function edit(int $repoID, int $objectID = 0, int $spaceID = 0)
    {
        $this->commonAction($repoID, $objectID);
        $repo = $this->repo->getByID($repoID);

        $this->scm->setEngine($repo);
        $branchList    = $this->scm->branch();
        $defaultBranch = empty($branchList) ? '' : key($branchList);
        $this->view->defaultBranch = $repo->defaultBranch ? $repo->defaultBranch : $defaultBranch;
        $this->view->branchList    = $branchList;

        if($_POST)
        {
            /* Prepare data. */
            $formData = form::data($this->config->repo->form->edit)
                ->skipSpecial('desc')
                ->get();
            $check = $this->repo->checkName($formData->name);
            if(!$check) return $this->sendError(array('name' => $this->lang->repo->error->repoNameInvalid));

            $res = $this->loadModel('gitfox')->addPushWebhook($repo);
            if(!$res) return $this->sendError(array('webhook' => isset($res['message']) ? $res['message'] : $this->lang->gitlab->failCreateWebhook));

            if($formData->acl == 'private' && empty($formData->members))
            {
                $this->sendError(array('members' => sprintf($this->lang->error->notempty, $this->lang->repo->members)));
            }

            if($formData) $response = $this->loadModel('gitfox')->apiUpdateRepo($repoID, $formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $members = $formData->acl == 'private' ? explode(',', $formData->members) : array();
            $this->repo->updateMembers($repoID, $members);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $noNeedSync = !empty($response) && !empty($response->id);

            $newRepo  = $this->repo->getByID($repoID);
            $actionID = $this->loadModel('action')->create('repo', $repoID, 'edited');
            $changes  = common::createChanges($repo, $newRepo);
            $this->action->logHistory($actionID, $changes);

            if(!$noNeedSync)
            {
                $link = $this->repo->createLink('showSyncCommit', "repoID=$repoID");
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
            }
            $link = !empty($spaceID) ? inLink('maintain', "inSpace=1&spaceID=$spaceID") : inLink('maintain');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        $this->repoZen->buildEditForm($repoID, $objectID);
        $this->view->inSpace = !empty($spaceID);
        $this->view->spaceID = $spaceID;

        $this->display();
    }

    /**
     * 删除代码库。
     * Delete repo.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function delete(int $repoID)
    {
        $error = $this->repoZen->checkDeleteError($repoID);
        if($error) return $this->send(array('result' => 'fail', 'callback' => 'zui.Modal.alert({content: {html: "' . $error . '"}})'));

        $this->repo->delete(TABLE_REPO, $repoID);
        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 用编辑器查看代码库文件。
     * View repo file with monaco editor.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $revision
     * @param  int    $showBug
     * @param  string $encoding
     * @access public
     * @return void
     */
    public function monaco(int $repoID, int $objectID = 0, string $entry = '', string $revision = 'HEAD', int $showBug = 0, string $encoding = '')
    {
        $this->commonAction($repoID, $objectID);

        $file  = $entry;
        $entry = $this->repo->decodePath($entry);
        $lines = '';
        if(strpos($entry, '#'))
        {
            $bugData = explode('#', $entry);
            $entry   = $bugData[0];
            $lines   = $bugData[1];
        }

        $entry    = urldecode($entry);
        $pathInfo = helper::mbPathinfo($entry);

        $repo = $this->repo->getByID($repoID);

        $branchID  = (string)$this->cookie->repoBranch;
        $dropMenus = array();
        $dropMenus = $this->repoZen->getBranchAndTagItems($repo, $branchID);

        if($this->app->tab == 'execution') $this->view->executionID = $objectID;
        $this->view->title     = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->view;
        $this->view->dropMenus = $dropMenus;
        $this->view->type      = 'view';
        $this->view->branchID  = $branchID;
        $this->view->showBug   = $showBug;
        $this->view->encoding  = $encoding;
        $this->view->repoID    = $repoID;
        $this->view->objectID  = $objectID;
        $this->view->repo      = $repo;
        $this->view->revision  = $revision;
        $this->view->file      = $file;
        $this->view->lines     = $lines;
        $this->view->entry     = $entry;
        $this->view->pathInfo  = $pathInfo;
        $this->view->tree      = $this->repoZen->getViewTree($repo, '', $revision);

        $this->display();
    }

    /**
     * 查看代码文件。
     * View repo file.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $revision
     * @param  int    $showBug
     * @param  string $encoding
     * @access public
     * @return void
     */
    public function view(int $repoID, int $objectID = 0, string $entry = '', string $revision = 'HEAD', int $showBug = 0, string $encoding = '')
    {
        set_time_limit(0);
        if($this->get->repoPath) $entry = $this->get->repoPath;
        if($repoID == 0) $repoID = $this->session->repoID;
        if($revision != 'HEAD')
        {
            helper::setCookie("repoBranch", $revision, $this->config->cookieLife, $this->config->webRoot, '', false, false);
            $this->cookie->set('repoBranch', $revision);
        }

        $this->commonAction($repoID, $objectID);
        $this->repoZen->setBackSession('view', true);
        $this->session->set('storyList', inlink('view',  "repoID=$repoID&objectID=$objectID&entry=$entry&revision=$revision&showBug=$showBug&encoding=$encoding"), 'product');

        return print($this->fetch('repo', 'monaco', "repoID=$repoID&objectID=$objectID&entry=$entry&revision=$revision&showBug=$showBug&encoding=$encoding"));
    }

    /**
     * 代码库目录树及提交信息页面。
     * Browse repo.
     *
     * @param  int    $repoID
     * @param  string $branchID
     * @param  int    $objectID
     * @param  string $path
     * @param  string $revision
     * @param  int    $refresh
     * @param  string $branchOrTag branch|tag
     * @param  string $type        dir|file
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $repoID = 0, string $branchID = '', int $objectID = 0, string $path = '', string $revision = 'HEAD', int $refresh = 0, string $branchOrTag = 'branch', string $type = 'dir', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $serverHeath = $this->loadModel('gitfox')->checkHealth();
        if(!$serverHeath) return $this->locate($this->createLink('gitfox', "devopsIntroduction"));

        $hasDevOpsLink = !empty($this->config->devopsLink) && $this->config->devopsLink == 'repo-browse';
        if(!$repoID && !empty($this->config->devopsLink) && $hasDevOpsLink) $repoID = (int)$this->config->lastRepo;
        $repoID = $this->repo->saveState($repoID, $objectID);

        /* Get path. */
        if($this->get->repoPath) $path = $this->get->repoPath;
        $path = $this->repo->decodePath($path);

        if($_POST)
        {
            $oldRevision = isset($this->post->revision[1]) ? $this->post->revision[1] : '';
            $newRevision = isset($this->post->revision[0]) ? $this->post->revision[0] : '';

            return $this->locate($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($path) . "&oldrevision=$oldRevision&newRevision=$newRevision"));
        }

        /* Set menu and session. */
        $repo = $this->repo->getByID($repoID);
        $this->commonAction($repoID, $objectID, $repo->spaceID);
        $this->repoZen->setBrowseSession($repo);

        /* Get repo and synchronous commit. */
        if(!$repo->synced) return $this->locate($this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID"));

        /* Set branch or tag for git. */
        $branchID = helper::safe64Decode(base64_decode($branchID));
        list($branchID, $branches, $tags) = $this->repoZen->setBranchTag($repo, $branchID);
        if($this->app->tab == 'devops' && !$this->repo->isSvn($repo) && empty($branches)) return $this->sendError($this->lang->repo->error->empty, true);

        $this->loadModel('setting')->setItem("{$this->app->user->account}.common.lastRepo", $repoID);

        /* Refresh repo. */
        $refresh = $refresh || $this->cookie->repoRefresh;
        if($refresh)
        {
            helper::setcookie('repoRefresh', 0);
            $this->repo->updateCommit($repoID, $objectID, $branchID);
        }

        /* Get revisions. */
        $this->app->loadClass('pager', true);
        $pager        = new pager($recTotal, $recPerPage, $pageID);
        $revisions    = $this->repoZen->getCommits($repo, $path, $revision, $type, $pager, $objectID);
        $lastRevision = empty($revisions) ? new stdclass() : current($revisions);

        /* Get files info. */
        $base64BranchID = helper::safe64Encode(base64_encode($branchID));
        $infos          = $this->repoZen->getFilesInfo($repo, $path, $branchID, $base64BranchID, $objectID);

        /* Synchronous commit only in root path. */
        if(empty($path) && $infos && empty($revisions)) $this->locate($this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID&branch=" . helper::safe64Encode(base64_encode($this->cookie->repoBranch))));
        if($branchOrTag == 'tag' && !in_array($branchID, $tags) && in_array($branchID, $branches)) $branchOrTag = 'branch';
        if($branchOrTag == 'branch' && in_array($branchID, $tags) && !in_array($branchID, $branches)) $branchOrTag = 'tag';

        $this->view->title          = $this->lang->repo->common;
        $this->view->repo           = $repo;
        $this->view->revisions      = $revisions;
        $this->view->revision       = $revision;
        $this->view->lastRevision   = $lastRevision;
        $this->view->infos          = $infos;
        $this->view->repoID         = $repoID;
        $this->view->branches       = $branches;
        $this->view->tags           = $tags;
        $this->view->branchID       = $branchID;
        $this->view->base64BranchID = $base64BranchID;
        $this->view->objectID       = $objectID;
        $this->view->pager          = $pager;
        $this->view->path           = urldecode($path);
        $this->view->logType        = $type;
        $this->view->cloneUrl       = $this->repo->getCloneUrl($repo);
        $this->view->repoPairs      = $this->repo->getRepoPairs($this->app->tab, $objectID);
        $this->view->branchOrTag    = $branchOrTag;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * 代码提交记录列表。
     * show repo log.
     *
     * @param  int    $repoID
     * @param  string $branchID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $source
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function log(int $repoID = 0, string $branchID = '', int $objectID = 0, string $entry = '', string $source = 'log', string $browseType = 'list', int $param = 0, int $recTotal = 0, int $recPerPage = 50, int $pageID = 1)
    {
        $repoID = $this->repo->saveState($repoID, $objectID);
        $this->commonAction($repoID, $objectID);
        if($this->get->repoPath) $entry = $this->get->repoPath;
        $this->repoZen->setBackSession('log', true);
        if($repoID == 0) $repoID = $this->session->repoID;

        $repo  = $this->repo->getByID($repoID);
        $file  = $entry;
        $entry = $this->repo->decodePath($entry);

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $pager->recPerPage = $recPerPage;

        if($_POST)
        {
            $oldRevision = isset($this->post->revision[1]) ? $this->post->revision[1] : '';
            $newRevision = isset($this->post->revision[0]) ? $this->post->revision[0] : '';

            $this->locate($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($file) . "&oldrevision=$oldRevision&newRevision=$newRevision"));
        }

        /* Set branch or tag for git. */
        $branchID = $branchID ? base64_decode(helper::safe64Decode($branchID)) : '';
        list($branchID, $branches, $tags) = $this->repoZen->setBranchTag($repo, $branchID);
        if($this->app->tab == 'devops' && !$this->repo->isSvn($repo) && empty($branches)) return $this->sendError($this->lang->repo->error->empty, true);

        /* Build the search form. */
        $browseType = strtolower($browseType);
        $queryID    = $browseType == 'bysearch' ? $param : 0;
        $branchID   = helper::safe64Encode(base64_encode($branchID));
        $actionURL  = $this->createLink('repo', 'log', "repoID={$repoID}&branchID={$branchID}&objectID={$objectID}&entry=&source={$source}&browseType=bysearch&param=myQueryID");
        $this->repoZen->buildSearchForm($queryID, $actionURL);

        $this->commonAction($repoID, $objectID);
        $query = $browseType == 'bysearch' ? $this->repoZen->getSearchForm($queryID, false) : null;
        $logs  = $this->repo->getCommits($repo, $entry, $branchID, 'dir', $pager, '', '', $query);
        if(count($logs) == 0 && $pageID != 1) $this->locate(inLink('log', "repoID=$repoID&branchID=$branchID&objectID=$objectID&entry=$entry&source=$source&browseType=$browseType&param=$param&recTotal=0&recPerPage=$recPerPage&pageID=1"));

        $revisionIds = array_column($logs, 'revision');
        $modelCommits = new stdClass();
        $modelCommits->stories = $this->loadModel('story')->getLinkedCommits($repoID, $revisionIds);
        $modelCommits->designs = $this->loadModel('design')->getLinkedCommits($repoID, $revisionIds);
        $modelCommits->tasks   = $this->loadModel('task')->getLinkedCommits($repoID, $revisionIds);
        $modelCommits->bugs    = $this->loadModel('bug')->getLinkedCommits($repoID, $revisionIds);
        /* Set tips and buttons for different relations. */
        foreach($logs as $logItem)
        {
            $logItem->relationFieldTips = '';
            foreach(array('designs' => 'design', 'stories' => 'story', 'tasks' => 'task' , 'bugs' => 'bug') as $fieldType => $moduleName)
            {
                if(!empty($modelCommits->{$fieldType}[$logItem->revision]))
                {
                    $fieldCommits = $modelCommits->{$fieldType}[$logItem->revision];
                    $logItem->relationFieldTips .= ' ' . $this->lang->repo->{$moduleName};
                    foreach($fieldCommits as $item) $item->url = !empty($item->id) ? $this->createLink($moduleName, 'view', "{$moduleName}ID=" . $item->id) : '';
                    $logItem->relationFieldTips .= ' #'.implode(' #', array_column($fieldCommits, 'id'));
                    $logItem->relationField[$fieldType] = $fieldCommits;
                }
            }
        }

        $this->view->repo       = $repo;
        $this->view->title      = $this->lang->repo->common;
        $this->view->logs       = $logs;
        $this->view->repoID     = $repoID;
        $this->view->objectID   = $objectID;
        $this->view->branchID   = $this->cookie->repoBranch;
        $this->view->entry      = urldecode($entry);
        $this->view->path       = urldecode($entry);
        $this->view->file       = urldecode($file);
        $this->view->pager      = $pager;
        $this->view->repoPairs  = $this->repo->getRepoPairs($this->app->tab, $objectID);
        $this->view->branches   = $branches;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->view->tags       = $tags;
        $this->view->source     = $source;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->display();
    }

    /**
     * 单个代码提交记录。
     * Show repo revision.
     *
     * @param int    $repoID
     * @param int    $objectID
     * @param string $revision
     * @access public
     * @return void
     */
    public function revision(int $repoID, int $objectID = 0, string $revision = '')
    {
        if($repoID == 0) $repoID = $this->session->repoID;
        $repo = $this->repo->getByID($repoID);
        $this->scm->setEngine($repo);

        $log      = $this->scm->log('', $revision, $revision);
        $revision = !empty($log[0]) ? $this->repo->getHistoryRevision($repoID, (string)$log[0]->revision) : '';

        /* SVN 用数字前驱 revision 作 oldRevision;首个 commit(revision=1) 与自身比,无差异。
         * git 用 sha^ 语法取父级。 */
        if($this->repo->isSvn($repo))
        {
            $newRevision = (int)$log[0]->revision;
            $oldRevision = $newRevision > 1 ? $newRevision - 1 : $newRevision;
        }
        else
        {
            $oldRevision = '^';
            if($revision) $oldRevision = "{$revision}^";
            $newRevision = $log[0]->revision;
        }

        $this->locate($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=&oldrevision=$oldRevision&newRevision={$newRevision}"));
    }

    /**
     * 代码blame信息。
     * Blame repo file.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $revision
     * @param  string $encoding
     * @access public
     * @return void
     */
    public function blame(int $repoID, int $objectID = 0, string $entry = '', string $revision = 'HEAD', string $encoding = '')
    {
        $this->commonAction($repoID, $objectID);

        if($this->get->repoPath) $entry = $this->get->repoPath;
        if($repoID == 0) $repoID = $this->session->repoID;
        $repo  = $this->repo->getByID($repoID);

        $file  = $entry;
        $entry = $this->repo->decodePath($entry);

        $this->scm->setEngine($repo);
        $encoding  = empty($encoding) ? 'utf-8' : $encoding;
        $encoding  = strtolower(str_replace('_', '-', $encoding));
        $blames    = $this->scm->blame($entry, $revision);
        $revisions = array();
        foreach($blames as $i => $blame)
        {
            if(isset($blame['revision'])) $revisions[$blame['revision']] = $blame['revision'];
            if($encoding != 'utf-8') $blames[$i]['content'] = helper::convertEncoding($blame['content'], $encoding);
        }

        $log = $this->repo->getHistoryRevision($repo->id, $revision, true);

        $this->view->title        = $this->lang->repo->common;
        $this->view->repoID       = $repoID;
        $this->view->branchID     = (string)$this->cookie->repoBranch;
        $this->view->objectID     = $objectID;
        $this->view->repo         = $repo;
        $this->view->revision     = $revision;
        $this->view->entry        = $entry;
        $this->view->file         = $file;
        $this->view->encoding     = str_replace('-', '_', $encoding);
        $this->view->revisionName = $log ? $this->repo->getGitRevisionName($log->revision, $log->commit) : $revision;
        $this->view->blames       = $blames;
        $this->display();
    }

    /**
     * 代码diff信息。
     * Show repo diff.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $oldRevision
     * @param  string $newRevision
     * @param  int    $showBug
     * @param  string $encoding
     * @param  int   $isBranchOrTag
     * @access public
     * @return void
     */
    public function diff(int $repoID, int $objectID = 0, string $entry = '', string $oldRevision = '', string $newRevision = '', int $showBug = 0, string $encoding = '', int $isBranchOrTag = 0)
    {
        $this->repoZen->setBackSession('diff', true);
        $newRevision = strtr($newRevision, '*', '-');
        $oldRevision = strtr($oldRevision, '*', '-');
        $oldRevision = urldecode(urldecode($oldRevision)); //Fix error.
        if($isBranchOrTag)
        {
            $oldRevision = urldecode(helper::safe64Decode($oldRevision));
            $newRevision = urldecode(helper::safe64Decode($newRevision));
        }

        $this->commonAction($repoID, $objectID);
        $repo  = $this->repo->getByID($repoID);

        if($this->get->repoPath) $entry = $this->get->repoPath;
        $file  = $entry;
        $entry = $this->repo->decodePath($entry);
        $lines = '';
        if(strpos($entry, '#'))
        {
            $bugData = explode('#', $entry);
            $entry   = $bugData[0];
            $lines   = $bugData[1];
        }

        $arrange = $this->cookie->arrange ? $this->cookie->arrange : 'inline';
        if($this->server->request_method == 'POST') return $this->repoZen->locateDiffPage($repoID, $objectID, $arrange, $isBranchOrTag, $file);

        $diffs    = array();
        $encoding = empty($encoding) ? 'utf-8' : $encoding;
        $encoding = strtolower(str_replace('_', '-', $encoding));
        if($oldRevision !== '')
        {
            $this->scm->setEngine($repo);
            $diffs = $this->scm->diff($entry, $oldRevision, $newRevision, 'yes', $isBranchOrTag ? 'isBranchOrTag': '');
        }

        if($encoding != 'utf-8') $diffs = $this->repoZen->encodingDiff($diffs, $encoding);
        if($arrange == 'appose') $diffs = $this->repo->getApposeDiff($diffs);

        $this->view->entry         = urldecode($entry);
        $this->view->encoding      = str_replace('-', '_', $encoding);
        $this->view->file          = $file;
        $this->view->lines         = $lines;
        $this->view->repoID        = $repoID;
        $this->view->branchID      = (string) $this->cookie->repoBranch;
        $this->view->objectID      = $objectID;
        $this->view->repo          = $repo;
        $this->view->diffs         = $diffs;
        $this->view->newRevision   = $newRevision;
        $this->view->oldRevision   = $oldRevision;
        $this->view->isBranchOrTag = $isBranchOrTag;
        $this->view->title         = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->diff;

        $this->display();
    }

    /**
     * 代码下载。
     * Download repo file.
     *
     * @param  int    $repoID
     * @param  string $path
     * @param  string $fromRevision
     * @param  string $toRevision
     * @param  string $type
     * @param  int    $isBranchOrTag
     * @access public
     * @return void
     */
    public function download(int $repoID, string $path, string $fromRevision = 'HEAD', string $toRevision = '', string $type = 'file', int $isBranchOrTag = 0)
    {
        if($this->get->repoPath) $path = $this->get->repoPath;
        $entry = $this->repo->decodePath($path);
        $repo  = $this->repo->getByID($repoID);

        $fromRevision = urldecode(helper::safe64Decode($fromRevision));
        $toRevision   = urldecode(helper::safe64Decode($toRevision));

        $this->commonAction($repoID);
        $this->scm->setEngine($repo);

        if($type === 'file')
        {
            $content = $this->scm->cat($entry, $fromRevision);
        }
        else
        {
            $content = $this->scm->diff($entry, $fromRevision, $toRevision, 'patch', $isBranchOrTag ? 'isBranchOrTag': '');
        }

        $fileName = basename(urldecode($entry));
        if($type != 'file') $fileName .= "r$fromRevision--r$toRevision.patch";

        $extension = strpos($fileName, '.') ? strrchr($fileName, '.') : '';
        $extension = ltrim($extension, '.');
        $this->fetch('file', 'sendDownHeader', array("fileName" => $fileName, "fileType" => $extension, "content" => $content));
    }

    /**
     * 设置DevOps指令.
     * Set Rules.
     *
     * @access public
     * @return void
     */
    public function setRules()
    {
        $this->loadModel('space')->setMenu();
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.repo.rules', json_encode($this->post->rules));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('story');
        if(is_string($this->config->repo->rules)) $this->config->repo->rules = json_decode($this->config->repo->rules, true);

        $this->view->title = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->setRules;
        $this->display();
    }

    /**
     * 显示提交同步进度。
     * Show sync commit.
     *
     * @param  int    $repoID
     * @param  int    $objectID  projectID|executionID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function showSyncCommit(int $repoID = 0, int $objectID = 0, string $branch = '')
    {
        $this->commonAction($repoID, $objectID);

        if($repoID == 0) $repoID = $this->session->repoID;
        if($branch) $branch = base64_decode(helper::safe64Decode($branch));

        $latestInDB = $this->repo->getLatestCommit($repoID);
        $this->view->title      = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->showSyncCommit;
        $this->view->version    = $latestInDB ? (int)$latestInDB->commit : 1;
        $this->view->repoID     = $repoID;
        $this->view->repo       = $this->repo->getByID($repoID);
        $this->view->objectID   = $objectID;
        $this->view->branch     = $branch;
        $this->view->browseLink = $this->repo->createLink('browse', "repoID=" . ($this->app->tab == 'devops' ? $repoID : '') . "&branchID=" . helper::safe64Encode(base64_encode($branch)) . "&objectID=$objectID", '', false) . "#app={$this->app->tab}";
        $this->display();
    }

    /**
     * 根据提交信息关联需求。
     * Link story to commit.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory(int $repoID, string $revision, string $browseType = '', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 10, int $pageID = 1)
    {
        if(!empty($_POST['stories'])) return $this->send($this->repoZen->linkObject($repoID, $revision, 'story'));

        $this->loadModel('story');
        $this->loadModel('release');
        $this->app->loadLang('productplan');

        $repo       = $this->repo->getByID($repoID);
        $productIds = explode(',', $repo->product);
        $products   = $this->loadModel('product')->getByIdList($productIds);
        $modules    = $this->repoZen->getLinkModules($products, 'story');
        $queryID    = $browseType == 'bysearch' ? (int)$param : 0;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->repoZen->buildStorySearchForm($repoID, $revision, $browseType, $queryID, $products, $modules);

        $this->view->modules    = $modules;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->allStories = $this->repoZen->getLinkStories($repoID, $revision, $browseType, $products, $orderBy, $pager, $queryID);
        $this->view->repoID     = $repoID;
        $this->view->revision   = $revision;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * 根据提交信息关联Bug。
     * Link bug to commit.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBug(int $repoID, string $revision = '', string $browseType = '', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 10, int $pageID = 1)
    {
        if(!empty($_POST['bugs'])) return $this->send($this->repoZen->linkObject($repoID, $revision, 'bug'));

        $this->loadModel('bug');
        $this->loadModel('release');
        $this->app->loadLang('productplan');

        $repo       = $this->repo->getByID($repoID);
        $productIds = explode(',', $repo->product);
        $products   = $this->loadModel('product')->getByIdList($productIds);
        $modules    = $this->repoZen->getLinkModules($products, 'bug');
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->repoZen->buildBugSearchForm($repoID, $revision, $browseType, $queryID, $products, $modules);

        $this->view->modules     = $modules;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->allBugs     = $this->repoZen->getLinkBugs($repoID, $revision, $browseType, $products, $orderBy, $pager, $queryID);
        $this->view->repoID      = $repoID;
        $this->view->revision    = $revision;
        $this->view->browseType  = $browseType;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->display();
    }

    /**
     * 根据提交信息关联任务。
     * Link task to commit.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkTask(int $repoID, string $revision = '', string $browseType = 'unclosed', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 10, int $pageID = 1)
    {
        if(!empty($_POST['tasks'])) return $this->send($this->repoZen->linkObject($repoID, $revision, 'task'));

        $this->loadModel('execution');
        $this->loadModel('product');
        $this->app->loadLang('task');

        /* Set browse type. */
        $browseType = strtolower($browseType);

        $repo     = $this->repo->getByID($repoID);
        $products = $this->loadModel('product')->getByIdList(explode(',', $repo->product));
        $modules  = $this->repoZen->getLinkModules($products, 'task');
        $queryID  = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get executions by product. */
        $executionPairs = $this->repoZen->getLinkExecutions($products);

        /* Build search form. */
        $this->repoZen->buildTaskSearchForm($repoID, $revision, $browseType, $queryID, $modules, $executionPairs);

        $this->view->modules      = $modules;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->allTasks     = $this->repoZen->getLinkTasks($repoID, $revision, $browseType, $products, $orderBy, $pager, $queryID, $executionPairs);
        $this->view->repoID       = $repoID;
        $this->view->revision     = $revision;
        $this->view->browseType   = $browseType;
        $this->view->param        = $param;
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->display();
    }

    /**
     * 取消提交信息的关联记录。
     * Unlink object and commit revision.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $objectType story|task|bug
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function unlink(int $repoID, string $revision, string $objectType, int $objectID)
    {
        $this->repo->unlink($repoID, $revision, $objectType, $objectID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'revision' => $revision));
    }

    /**
     * 导入版本库。
     * Import repos.
     *
     * @param  int    $spaceID
     * @param  string $type
     * @param  int    $providerID
     * @param  string $groupID
     * @param  int    $isTryAgain
     * @access public
     * @return void
     */
    public function import(int $spaceID = 0, string $type = 'GitLab', int $providerID = 0, string $groupID = '', int $isTryAgain = 0)
    {
        if($this->viewType !== 'json') $this->commonAction();
        if($_POST)
        {
            $this->repoZen->setImportFormConfig($type, (int)$this->post->providerID);
            $formData = form::data($this->config->repo->form->import)->get();
            $this->session->set('importRepo', json_encode($formData));

            $result   = $this->repo->import($formData);
            if(dao::isError()) $this->sendError(dao::getError());
            return $this->send(array('result' => 'success', 'message' => '', 'load' => $this->createLink('repo', 'ajaxShowImportProgress', "repoID={$result->id}&spaceID={$spaceID}")));
        }

        if($isTryAgain && $this->session->importRepo)
        {
            $importRepo = json_decode($this->session->importRepo);
            $type       = zget($importRepo, 'origin', 'GitLab');
            $providerID = zget($importRepo, 'providerID', 0);
            $groupID    = $type == 'Subversion' ? '' : zget($importRepo, 'organize', '');
            $groupID    = urlencode($groupID);
            $type       = zget($importRepo, 'origin', 'GitLab');
        }
        $this->repoZen->buildImportForm($providerID, $groupID, $type);

        $this->view->title      = $this->lang->repo->import;
        $this->view->products   = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $this->view->spaces     = $this->loadModel('space')->getPairs($this->app->user->admin ? '' : $this->app->user->account);
        $this->view->type       = $type;
        $this->view->importRepo = $isTryAgain ? json_decode($this->session->importRepo) : array();
        $this->view->tryAgain   = $isTryAgain;
        $this->view->spaceID    = $spaceID;
        $this->view->inSpace    = !empty($spaceID);
        $this->display();
    }

    /**
     * 获取代码对比编辑器内容。
     * Get diff editor content by ajax.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $oldRevision
     * @param  string $newRevision
     * @param  int    $showBug     // Used for biz.
     * @param  string $encoding
     * @param  int    $showLinkObject
     * @access public
     * @return void
     */
    public function ajaxGetDiffEditorContent(int $repoID, int $objectID = 0, string $entry = '', string $oldRevision = '', string $newRevision = '', int $showBug = 0, string $encoding = '', int $showLinkObject = 1)
    {
        $this->app->loadConfig('misc');
        if(!$entry) $entry = (string) $this->cookie->repoCodePath;

        $file      = $entry;
        $repo      = $this->repo->getByID($repoID);
        $entry     = urldecode($this->repo->decodePath($entry));
        /* 前端 diff.ui.js 把 revision 走 btoa(encodeURIComponent(...)) 加密;此处走已有的 decodeEditorRevision 复原。 */
        $revision  = $this->decodeEditorRevision(str_replace('*', '-', $oldRevision));
        $nRevision = $this->decodeEditorRevision(str_replace('*', '-', $newRevision));

        $entry    = urldecode($entry);
        $pathInfo = pathinfo($entry);
        $encoding = empty($encoding) ? 'utf-8' : $encoding;
        $encoding = strtolower(str_replace('_', '-', $encoding));
        $lines    = '';
        if(strpos($entry, '#'))
        {
            $bugData = explode('#', $entry);
            $entry   = $bugData[0];
            $lines   = $bugData[1];
        }

        $this->scm->setEngine($repo);
        $info = $this->scm->info($entry, $nRevision);

        $this->view->title       = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->diff;
        $this->view->type        = 'diff';
        $this->view->encoding    = str_replace('-', '_', $encoding);
        $this->view->repoID      = $repoID;
        $this->view->objectID    = $objectID;
        $this->view->repo        = $repo;
        $this->view->revision    = $nRevision;
        $this->view->oldRevision = $revision;
        $this->view->file        = $file;
        $this->view->lines       = $lines;
        $this->view->entry       = $entry;
        $this->view->info        = $info;
        $this->view->content     = '';
        $this->view->pathInfo    = $pathInfo;
        $this->view->suffix      = 'c';
        $this->view->blames      = array();
        $this->view->showEditor  = true;
        $this->view->canReview   = true;
        $this->view->showBug     = $showBug;

        $this->view->showLinkObject = $showLinkObject;
        $this->display('repo', 'ajaxgeteditorcontent');
    }

    /**
     * 获取代码详情的编辑器内容。
     * Get editor content by ajax.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $revision
     * @param  int    $showBug
     * @param  string $encoding
     * @param  int    $showLinkObject
     * @access public
     * @return void
     */
    public function ajaxGetEditorContent(int $repoID, int $objectID = 0, string $entry = '', string $revision = 'HEAD', int $showBug = 0, string $encoding = '', int $showLinkObject = 1)
    {
        $this->app->loadConfig('misc');
        if(!$entry) $entry = (string) $this->cookie->repoCodePath;

        $file     = $entry;
        $repo     = $this->repo->getByID($repoID);
        $entry    = urldecode($this->repo->decodePath($entry));
        $revision = str_replace('*', '-', $revision);
        $lines    = '';
        if(strpos($entry, '#'))
        {
            $bugData = explode('#', $entry);
            $entry   = $bugData[0];
            $lines   = $bugData[1];
            $file    = $this->repo->encodePath($entry);
        }

        $this->scm->setEngine($repo);
        $info = $this->scm->info($entry, $revision);
        $path = $entry ? $info->path : '';
        if($info->kind == 'dir') $this->locate($this->repo->createLink('browse', "repoID=$repoID&branchID=&objectID=$objectID&path=" . $this->repo->encodePath($path) . "&revision=$revision"));

        $content  = $this->scm->cat($entry, $revision);
        $entry    = urldecode($entry);
        $pathInfo = pathinfo($entry);
        $encoding = empty($encoding) ? 'utf-8' : $encoding;
        $encoding = strtolower(str_replace('_', '-', $encoding));

        $suffix   = '';
        if(isset($pathInfo["extension"])) $suffix = strtolower($pathInfo["extension"]);
        if(!$suffix or (!array_key_exists($suffix, $this->config->program->suffix) and strpos($this->config->repo->images, "|$suffix|") === false)) $suffix = $this->repoZen->isBinary($content, $suffix) ? 'binary' : 'c';

        if(strpos($this->config->repo->images, "|$suffix|") !== false)
        {
            $content = base64_encode($content);
        }
        elseif($encoding != 'utf-8')
        {
            $content = helper::convertEncoding($content, $encoding);
        }

        $this->view->title       = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->view;
        $this->view->type        = 'view';
        $this->view->showBug     = $showBug;
        $this->view->repoID      = $repoID;
        $this->view->repo        = $repo;
        $this->view->revision    = base64_encode($revision);
        $this->view->oldRevision = '';
        $this->view->file        = $file;
        $this->view->lines       = $lines;
        $this->view->entry       = $entry;
        $this->view->suffix      = $suffix;
        $this->view->content     = $content ? $content : '';
        $this->view->pathInfo    = $pathInfo;
        $this->view->objectID    = $objectID;
        $this->view->showEditor  = (strpos($this->config->repo->images, "|$suffix|") === false and $suffix != 'binary') ? true : false;
        $this->view->canReview   = true;

        $this->view->showLinkObject = $showLinkObject;
        $this->display();
    }

    /**
     * 异步同步代码提交记录。
     * Ajax sync comment.
     *
     * @param  int    $repoID
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxSyncCommit(int $repoID = 0, string $type = 'batch')
    {
        set_time_limit(0);
        $repo = $this->repo->getByID($repoID);
        if(empty($repo)) return print($this->config->repo->repoSyncLog->finish);
        if($repo->synced) return print($this->config->repo->repoSyncLog->finish);

        $this->commonAction($repoID);
        $this->scm->setEngine($repo);

        $branchID = (string)$this->cookie->syncBranch;
        if(!$this->cookie->syncBranch && !$this->repo->isSvn($repo))
        {
            $branches = $this->scm->branch();
            if(empty($branches)) return print($this->lang->repo->error->empty);

            $branchID = current($branches);
        }

        $branches = $this->repoZen->getSyncBranches($branchID);

        $logs    = array();
        $version = 1;

        $commitCount = $this->repo->saveCommit($repoID, $logs, $version, $branchID);
        echo $this->repoZen->checkSyncResult($repo, $branches, $branchID, $commitCount, $type);
    }

    /**
     * 异步同步代码分支提交记录。
     * Ajax sync git branch comment.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return void
     */
    /**
     * 触发镜像仓库同步。
     * Ajax trigger mirror sync for a mirror repo.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxMirrorSync(int $repoID = 0)
    {
        $repo = $this->repo->getByID($repoID);
        if(empty($repo)) return $this->send(array('result' => 'fail', 'message' => $this->lang->repo->error->noFound));

        $response = $this->loadModel('gitfox')->apiMirrorSync((int)$repo->id);
        if(empty($response) || !is_object($response)) return $this->send(array('result' => 'fail', 'message' => $this->lang->repo->mirror->syncRequestFailed));

        $code    = isset($response->code)    ? (string)$response->code    : '';
        $message = isset($response->message) ? (string)$response->message : '';
        if($code !== 'success') return $this->send(array('result' => 'fail', 'message' => $message ? $message : $this->lang->repo->mirror->syncFailed));

        return $this->send(array(
            'result'   => 'success',
            'message'  => $message ? $message : $this->lang->repo->mirror->syncTriggered,
            'callback' => 'mirrorSyncDelayedReload'
        ));
    }

    /**
     * 查询镜像仓库同步进度。
     * Ajax get mirror sync progress for a mirror repo.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxMirrorSyncProgress(int $repoID = 0)
    {
        $repo = $this->repo->getByID($repoID);
        if(empty($repo)) return $this->send(array('result' => 'fail', 'message' => $this->lang->repo->error->noFound));

        $progress = $this->loadModel('gitfox')->apiGetMirrorSyncProgress((int)$repo->id);
        $status   = (!empty($progress) && is_object($progress) && !empty($progress->status))  ? (string)$progress->status  : '';
        $failure  = (!empty($progress) && is_object($progress) && !empty($progress->failure)) ? (string)$progress->failure : '';

        return $this->send(array(
            'result'  => 'success',
            'status'  => $status,
            'failure' => $failure
        ));
    }

    public function ajaxSyncBranchCommit(int $repoID = 0, string $branch = '')
    {
        set_time_limit(0);
        $repo = $this->repo->getByID($repoID);
        if(empty($repo)) return;
        if($branch) $branch = base64_decode(helper::safe64Decode($branch));

        $this->scm->setEngine($repo);

        $this->repoZen->setRepoBranch($branch);
        helper::setcookie("syncBranch", $branch);

        $latestInDB = $this->dao->select('t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->orderBy('t1.time')
            ->limit(1)
            ->fetch();

        $version  = empty($latestInDB) ? 1 : $latestInDB->commit + 1;
        $logs     = array();
        $revision = $version == 1 ? 'HEAD' : $latestInDB->commit;

        $logs = $this->scm->getCommits($revision, $this->config->repo->batchNum, $branch);
        $commitCount = $this->repo->saveCommit($repoID, $logs, $version, $branch);
        if(empty($commitCount))
        {
            if($branch) $this->repo->saveExistCommits4Branch($repo->id, $branch);

            helper::setcookie("syncBranch", $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
            $this->repo->markSynced($repoID);
            return print('finish');
        }

        $this->dao->update(TABLE_REPO)->set('commits=commits + ' . $commitCount)->where('id')->eq($repoID)->exec();
        echo $commitCount;
    }

    /**
     * 获取SVN目录。
     * Ajax get svn dir.
     *
     * @param  int    $repoID
     * @param  string $path
     * @access public
     * @return void
     */
    public function ajaxGetSVNDirs(int $repoID, string $path = '')
    {
        $repo = $this->repo->getByID($repoID);
        if(!$this->repo->isSvn($repo)) return print(json_encode(array()));

        $path = $this->repo->decodePath($path);
        $dirs = array();
        if(empty($path))
        {
            $dirs['/'] = '';
            if(empty($repo->prefix)) $path = '/';
        }

        $tags = $this->loadModel('svn')->getRepoTags($repo, $path);
        if($tags)
        {
            $dirs['/'] = $this->repo->encodePath($path);
            foreach($tags as $dirPath => $dirName) $dirs[$dirPath] = $this->repo->encodePath($dirPath);
        }

        echo json_encode($dirs);
    }

    /**
     * 获取1.5级导航数据。
     * Ajax get drop menu.
     *
     * @param  int    $repoID
     * @param  string $type
     * @param  string $method
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $repoID, string $module = 'repo', string $method = 'browse', int $projectID = 0)
    {
        if($module == 'reporeviewflow' && $method == 'edit') $method = 'browse';
        if($module == 'repo' && !in_array($method, array('review', 'diff', 'browsetag', 'browsebranch', 'log'))) $method = 'browse';
        if($module == 'ppm' && $method != 'create')  $method = 'browse';
        if($module == 'pipeline') $method = 'browse';
        if($module == 'compile' and $method == 'logs') $method = 'browse';
        if($module == 'bug' and $method == 'view')
        {
            $module = 'repo';
            $method = 'review';
        }
        if($module == 'repobranchrule' and $method == 'setbranchrule')
        {
            $module = 'repo';
            $method = 'browse';
        }
        if($module == 'artifact' and $method == 'view')
        {
            $module = 'artifact';
            $method = 'browse';
        }

        $params = '';
        if($projectID)
        {
            if($method == 'browse' || $method == 'log') $params = "&branchID=&objectID=$projectID";
            if(in_array($method, array('browsetag', 'browsebranch'))) $params = "&objectID=$projectID";
        }

        /* Get repo group by type. */
        $repoGroup = $this->repo->getRepoGroup('project', $projectID);
        $link      = $this->createLink($module, $method, "repoID=%s" . $params);
        if($module == 'pipeline') $link = $this->createLink($module, $method, "space=0&repoID=%s&type=repo" . $params);
        if($module == 'artifact') $link = $this->createLink($module, $method, "space=0&repoID=%s&type=repo");

        $this->view->repoID    = $repoID;
        $this->view->repoGroup = $repoGroup;
        $this->view->link      = $link;

        $this->display();
    }

    /**
     * 根据产品ID获取项目列表。
     * Get projects list by product id list by ajax.
     *
     * @access public
     * @return void
     */
    public function ajaxProjectsOfProducts()
    {
        $productIds = $this->post->products ? explode(',', $this->post->products) : array();
        if(empty($productIds))
        {
            $products   = $this->loadModel('product')->getPairs('', 0, '', 'all');
            $productIds = array_keys($products);
        }
        /* Get all projects that can be accessed. */
        $accessProjects = $this->loadModel('product')->getProjectPairsByProductIDList($productIds);

        $options = array();
        foreach($accessProjects as $projectID => $project)
        {
            $options[] = array('text' => $project, 'value' => $projectID);
        }
        return print(json_encode($options));
    }


    /**
     * 获取各个服务器下的项目。
     * Ajax get projects by server.
     *
     * @param  int    $serverID
     * @access public
     * @return void
     */
    public function ajaxGetProjects(int $serverID)
    {
        $repos = $this->repo->ajaxGetGitFoxProjects($serverID);
        return print(json_encode($this->repoZen->buildRepoPaths(array_column($repos, 'text', 'value'))));
    }

    /**
     * 根据服务器ID获取分组。
     * Ajax get groups by server.
     *
     * @param  int    $serverID
     * @access public
     * @return void
     */
    public function ajaxGetGroups(int $serverID)
    {
        $options = $this->repo->getGroups($serverID);

        $result = new stdclass();
        $result->options = $options;

        return print(json_encode($result));
    }

    /**
     * 根据代码库ID获取产品列表。
     * Ajax:: Load product by repoID.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxLoadProducts(int $repoID)
    {
        $productPairs = $this->repo->getProductsByRepo($repoID);

        $options = array();
        $options[] = array('text' => '', 'value' => '');;
        foreach($productPairs as $productID => $productName)
        {
            $options[] = array('text' => $productName, 'value' => $productID);
        }
        return print(json_encode($options));
    }

    /**
     * 根据Url获取代码库信息。
     * API: get repo by url.
     *
     * @access public
     * @return void
     */
    public function apiGetRepoByUrl()
    {
        $url    = urldecode($this->post->repoUrl);
        $result = $this->repo->getRepoByUrl($url);
        if($result['result'] == 'fail') return $this->send($result);

        $repo = $result['data'];
        $fileServer = new stdclass();
        $fileServer->fileServerUrl      = $repo->fileServerUrl;
        $fileServer->fileServerAccount  = $repo->fileServerAccount;
        $fileServer->fileServerPassword = $repo->fileServerPassword;
        return $this->send($fileServer);
    }

    /**
     * 获取DevOps指令配置。
     * API: get rules.
     *
     * @access public
     * @return void
     */
    public function ajaxGetRules()
    {
        return $this->send(array('status' => 'success', 'rules' => $this->config->repo->rules));
    }

    /**
     * Ajax get executions.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function ajaxGetExecutions(int $productID, int $branch = 0)
    {
        $executions = $this->repo->getExecutionPairs($productID, $branch);

        $options = array();
        foreach($executions as $executionID => $executionName)
        {
            $options[] = array('text' => $executionName, 'value' => $executionID);
        }
        return print(json_encode($options));
    }

    /**
     * 下载代码。
     * Download zip code.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function downloadCode(int $repoID, string $branch = '')
    {
        $tempDownloadDir = $this->app->getTmpRoot() . 'cache/repo/';
        if(!is_dir($tempDownloadDir) && !mkdir($tempDownloadDir, 0755, true) && !is_dir($tempDownloadDir))
        {
            return $this->sendError(sprintf($this->lang->repo->error->noWritable, $tempDownloadDir), true);
        }
        if(!is_writable($tempDownloadDir)) return $this->sendError(sprintf($this->lang->repo->error->noWritable, $tempDownloadDir), true);

        $repo = $this->repo->getByID($repoID);

        $this->scm = $this->app->loadClass('scm');
        $this->scm->setEngine($repo);
        $downloadSource = $this->scm->getDownloadUrl($branch, $tempDownloadDir);
        if($downloadSource === false || $downloadSource === '') return $this->sendError($this->lang->fail, true);

        if(is_file($downloadSource))
        {
            $packageFile = $downloadSource;
        }
        else
        {
            $packageFile = tempnam($tempDownloadDir, 'repo_');
            if($packageFile === false) return $this->sendError(sprintf($this->lang->repo->error->noWritable, $tempDownloadDir), true);

            $zipContent = file_get_contents($downloadSource);
            if($zipContent === false || file_put_contents($packageFile, $zipContent) === false)
            {
                if(is_file($packageFile)) unlink($packageFile);
                return $this->sendError($this->lang->fail, true);
            }
        }

        $zipContent = file_get_contents($packageFile);
        unlink($packageFile);
        if($zipContent === false) return $this->sendError($this->lang->fail, true);

        $downloadName = $branch === '' ? $repo->name : $branch;
        $this->loadModel('file')->sendDownHeader("{$downloadName}.zip", 'zip', $zipContent);
    }

    /**
     * 根据代码库和提交获取关联信息的标题列表。
     * Get relation by commit.
     *
     * @param  int    $repoID
     * @param  string $commit
     * @access public
     * @return void
     */
    public function ajaxGetCommitRelation(int $repoID, string $commit)
    {
        $titleList = $this->repo->getRelationByCommit($repoID, $commit);
        return $this->send(array('titleList' => $titleList));
    }

    /**
     * 根据对象ID和对象类型获取关联信息。
     * Get relation story, task, bug info.
     *
     * @param  int    $objectID
     * @param  string $objectType  story|task|bug
     * @access public
     * @return void
     */
    public function ajaxGetRelationInfo(int $objectID, string $objectType = 'story')
    {
        $this->app->loadLang('release');
        $this->view->object     = $this->loadModel($objectType)->getById($objectID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList($objectType, $objectID);
        $this->view->objectID   = $objectID;
        $this->view->objectType = $objectType;
        $this->display();
    }

    /**
     * 通过行号和版本获取代码库的提交信息。
     * Ajax get commit info.
     *
     * @access public
     * @return void
     */
    public function ajaxGetCommitInfo()
    {
        $repo  = $this->repo->getByID((int)$this->post->repoID);
        $entry = $this->repo->decodePath($this->post->entry);

        $revision       = $this->post->revision == 'HEAD' ? 'HEAD' : $this->decodeEditorRevision((string)$this->post->revision);
        $sourceRevision = $this->post->sourceRevision == 'HEAD' ? 'HEAD' : $this->decodeEditorRevision((string)$this->post->sourceRevision);

        $this->scm->setEngine($repo);
        $blames = $this->scm->blame($entry, $revision);
        if(!$blames) $blames =$this->scm->blame($entry, $sourceRevision);

        return $this->send(array('result' => 'success', 'blames' => $blames));
    }

    /**
     * Decode revision values posted by editor pages.
     *
     * Some editor entry points post raw branch names, while others post
     * base64/safe64 encoded values. Only decode when the input can round-trip
     * as a valid encoded revision; otherwise keep the raw value.
     *
     * @param  string $revision
     * @access protected
     * @return string
     */
    protected function decodeEditorRevision(string $revision): string
    {
        $revision = urldecode($revision);
        if($revision === '') return $revision;

        $decoded = helper::safe64Decode($revision);
        if($decoded === false || $decoded === '') return $revision;

        if(helper::safe64Encode($decoded) === $revision || base64_encode($decoded) === $revision) return urldecode($decoded);

        return $revision;
    }

    /**
     * 获取Gitlab的文件信息。
     * Get gitlab files.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @param  string $path
     * @access public
     * @return void
     */
    public function ajaxGetFiles(int $repoID, string $branch = '', string $path = '')
    {
        $repo = $this->repo->getByID($repoID);
        if($path) $path = helper::safe64Decode($path);

        return print(json_encode($this->repoZen->getViewTree($repo, $path, $branch)));
    }

    /**
     * 获取文件最后一次提交信息。
     * Get file last commit info.
     *
     * @access public
     * @return void
     */
    public function ajaxGetFileCommitInfo()
    {
        $repo   = $this->repo->getByID((int)$this->post->repoID);
        $commit = $this->loadModel('gitlab')->getFileLastCommit($repo, (string)$this->post->path, (string)$this->post->branch);
        $commit->comment = $this->repo->replaceCommentLink($commit->message);
        echo json_encode($commit);
    }

    /**
     * 在批量导入代码库页面隐藏代码库。
     * Hidden repo in import page.
     *
     * @access public
     * @return void
     */
    public function ajaxHiddenRepo()
    {
        $repoID   = $this->post->repoID;
        $serverID = $this->post->serverID;

        $reposID = $this->loadModel('setting')->getItem('owner=system&module=repo&section=hiddenRepo&key=' . $serverID);
        if(!$reposID) $reposID = $repoID;

        $repoIDList = explode(',', $reposID);
        if(!in_array($repoID, $repoIDList)) $reposID .= ",{$repoID}";

        $this->setting->setItem('system.repo.hiddenRepo.' . $serverID, $reposID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * 在批量导入代码库页面显示代码库。
     * Show repo in import page.
     *
     * @access public
     * @return void
     */
    public function ajaxShowRepo()
    {
        $repoID   = $this->post->repoID;
        $serverID = $this->post->serverID;

        $reposID = $this->loadModel('setting')->getItem('owner=system&module=repo&section=hiddenRepo&key=' . $serverID);
        $reposID = str_replace(",{$repoID},", "", ",{$reposID},");

        $this->setting->setItem('system.repo.hiddenRepo.' . $serverID, trim($reposID, ','));
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * 通过ajax获取代码库的分支和标签列表。
     * Ajax: Get branches and tags.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxGetBranchesAndTags(int $repoID)
    {
        $repo = $this->repo->getByID($repoID);
        $scm  = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $branches = $scm->branch();
        $tagList  = $scm->tags();

        $tags = array();
        foreach($tagList as $tag) $tags[$tag] = $tag;

        echo json_encode(array('branches' => $branches, 'tags' => $tags));
    }

    /**
     * 浏览Tag列表。
     * Browse tag list.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $keyword
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseTag(int $repoID, int $objectID = 0, string $keyword = '', string $orderBy = 'date_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $repoID = $this->repoZen->processRepoID($repoID, $objectID);
        $this->commonAction($repoID, $objectID);

        $keyword = str_replace(' ', '+', urldecode($keyword));
        $keyword = htmlspecialchars(base64_decode($keyword));

        $repo = $this->repo->getByID($repoID);

        $this->scm->setEngine($repo);
        $tagList = $this->scm->tags($keyword ? $keyword : 'all', 'HEAD', true, $orderBy, $recPerPage, $pageID);
        if(count($tagList) == 0 && $pageID != 1) $this->locate(inLink('browseTag', "repoID=$repoID&objectID=$objectID&keyword=$keyword&orderBy=$orderBy&recTotal=0&recPerPage=$recPerPage&pageID=1"));

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $pager->recPerPage = $recPerPage;
        $pager->recTotal = count($tagList) < $pager->recPerPage ? $pager->recPerPage * $pager->pageID : $pager->recPerPage * ($pager->pageID + 1);

        $committers      = $this->loadModel('user')->getCommiters('account');
        $showCreatedDate = false;
        foreach($tagList as &$tag)
        {
            $tag->repoID   = $repoID;
            $tag->tagName  = urlencode(helper::safe64Encode($tag->name));
            $tag->objectID = $objectID;

            $tag->commitID = isset($tag->commit->id) ? $tag->commit->id : '';
            if(isset($tag->commit->sha)) $tag->commitID = $tag->commit->sha;
            $tag->commitID = substr($tag->commitID, 0, 10);

            $tag->committer = isset($tag->commit->author_name) ? $tag->commit->author_name : '';
            if(isset($tag->commit->author->identity->name)) $tag->committer = $tag->commit->author->identity->name;
            $tag->committer = zget($committers, $tag->committer);

            $tag->createdDate = isset($tag->createdBy->when) ? date('Y-m-d H:i:s', strtotime($tag->createdBy->when)) : '';
            if($tag->createdDate) $showCreatedDate = true;
            $tag->createdBy = isset($tag->createdBy->identity->name) ? $tag->createdBy->identity->name : '';

            $tag->date = isset($tag->commit->committed_date) ? date('Y-m-d H:i:s', strtotime($tag->commit->committed_date)) : '';
            if(isset($tag->commit->committer->when)) $tag->date = date('Y-m-d H:i:s', strtotime($tag->commit->committer->when));
        }

        if(!$showCreatedDate) unset($this->config->repo->dtable->tag->fieldList['createdDate']);

        $this->view->title    = $this->lang->repo->browseTag;
        $this->view->repoID   = $repoID;
        $this->view->objectID = $objectID;
        $this->view->repo     = $repo;
        $this->view->pager    = $pager;
        $this->view->tagList  = $tagList;
        $this->view->orderBy  = $orderBy;
        $this->view->keyword  = base64_encode($keyword);
        $this->view->users    = $this->user->getPairs('noletter');
        $this->display();
    }

    /**
     * 浏览 webhook 列表。
     * Browse webhook list.
     *
     * @param  int    $repoID
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseWebhooks(int $repoID, string $orderBy = 'createdDate_desc', int $recPerPage = 20, int $pageID = 1)
    {
        $repoID = $this->repoZen->processRepoID($repoID, 0);
        $this->commonAction($repoID, 0);

        $repo     = $this->repo->getByID($repoID);
        $webhooks = $this->loadModel('gitfox')->apiGetHooks((int)$repo->id);
        if(!$webhooks) $webhooks = array();

        foreach($webhooks as &$webhook)
        {
            $webhook->repoID = $repoID;
            $webhook->name   = zget($webhook, 'displayName', '');
            $webhook->status = !empty($webhook->enabled) ? 'enabled' : 'disabled';

            if(isset($webhook->latestExecutionResult))
            {
                $webhook->latestStatus = $webhook->latestExecutionResult == 'success' ? 'success' : 'fail';
            }
            else
            {
                $webhook->latestStatus = 'pending';
            }
        }

        list($order, $sort) = explode('_', $orderBy);
        $orderList = array();
        foreach($webhooks as $orderWebhook)
        {
            if(!isset($orderWebhook->$order)) continue;
            $orderList[] = $orderWebhook->$order;
        }
        if($orderList) array_multisort($orderList, $sort == 'desc' ? SORT_DESC : SORT_ASC, $webhooks);

        $this->app->loadClass('pager', true);
        $webhookTotal = count($webhooks);
        $pager        = new pager($webhookTotal, $recPerPage, $pageID);
        $webhooks     = array_chunk($webhooks, (int)$pager->recPerPage);
        if($webhooks && !isset($webhooks[$pageID - 1])) $pageID = 1;

        $this->view->title    = $this->lang->repo->browseWebhooks;
        $this->view->repoID   = $repoID;
        $this->view->repo     = $repo;
        $this->view->pager    = $pager;
        $this->view->webhooks = empty($webhooks) ? array() : $webhooks[$pageID - 1];
        $this->view->orderBy  = $orderBy;
        $this->display();
    }

    /**
     * 创建 webhook。
     * Create a webhook.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function createWebhook(int $repoID)
    {
        $repoID = $this->repoZen->processRepoID($repoID, 0);
        $this->commonAction($repoID, 0);

        if($_POST)
        {
            $repo = $this->repo->getByID($repoID);

            $formData = form::data($this->config->repo->form->createWebhook)->get();
            $webhook  = $this->repoZen->buildWebhook($formData, $repo);
            if(dao::isError()) $this->sendError(dao::getError());

            $result = $this->loadModel('gitfox')->apiCreateHook((int)$repo->id, $webhook);
            if(empty($result) || empty($result->id)) $this->sendError($this->lang->fail);

            $this->loadModel('action')->create('repo', $repoID, 'createwebhook');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('repo', 'browseWebhooks', "repoID=$repoID")));
        }

        $this->view->title  = $this->lang->repo->createWebhook;
        $this->view->repoID = $repoID;
        $this->display();
    }

    /**
     * 编辑 webhook。
     * Edit a webhook.
     *
     * @param  int    $repoID
     * @param  int    $webhookID
     * @access public
     * @return void
     */
    public function editWebhook(int $repoID, int $webhookID)
    {
        $repoID = $this->repoZen->processRepoID($repoID, 0);
        $this->commonAction($repoID, 0);

        $repo    = $this->repo->getByID($repoID);
        $webhook = $this->loadModel('gitfox')->apiGetHooks((int)$repo->id, $webhookID);

        if($_POST)
        {
            $formData = form::data($this->config->repo->form->editWebhook)->get();

            $newWebhook = $this->repoZen->buildWebhook($formData, $repo, $webhook);
            if(empty(get_object_vars($newWebhook))) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('repo', 'browseWebhooks', "repoID=$repoID")));
            if(dao::isError()) $this->sendError(dao::getError());

            $result = $this->loadModel('gitfox')->apiUpdateWebhook((int)$repo->id, $webhookID, $newWebhook);
            if(!$result) $this->sendError($this->lang->fail);

            $this->loadModel('action')->create('repo', $repoID, 'editwebhook');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('repo', 'browseWebhooks', "repoID=$repoID")));
        }

        $this->view->title   = $this->lang->repo->editWebhook;
        $this->view->repoID  = $repoID;
        $this->view->webhook = $webhook;
        $this->display();
    }

    /**
     * 浏览 webhook 日志列表。
     * Browse webhook execution logs.
     *
     * @param  int    $repoID
     * @param  int    $webhookID
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function logWebhook(int $repoID, int $webhookID, string $orderBy = 'created_desc', int $recPerPage = 20, int $pageID = 1)
    {
        $repoID = $this->repoZen->processRepoID($repoID, 0);
        $this->commonAction($repoID, 0);

        $repo = $this->repo->getByID($repoID);
        $logs = $this->loadModel('gitfox')->apiGetWebhookExecution((int)$repo->id, $webhookID);
        if(!$logs) $logs = array();

        foreach($logs as &$log)
        {
            $log->createdDate = zget($log, 'createdDate', '');
            $log->triggerType = zget($log, 'triggerType', '');
            $log->url         = zget($log, 'reqUrl', '');
            $log->result      = zget($log, 'result', '') == 'fatal_error' ? 'fail' : zget($log, 'result', '');
            $log->repoID      = $repoID;
            $log->webhookID   = $webhookID;
        }

        list($order, $sort) = explode('_', $orderBy);
        $orderList = array();
        foreach($logs as $orderLog)
        {
            if(!isset($orderLog->$order)) continue;
            $orderList[] = $orderLog->$order;
        }
        if($orderList) array_multisort($orderList, $sort == 'desc' ? SORT_DESC : SORT_ASC, $logs);

        $this->app->loadClass('pager', true);
        $logTotal = count($logs);
        $pager    = new pager($logTotal, $recPerPage, $pageID);
        $logs     = array_chunk($logs, (int)$pager->recPerPage);
        if($logs && !isset($logs[$pageID - 1])) $pageID = 1;

        $this->view->title     = $this->lang->repo->logWebhook;
        $this->view->repoID    = $repoID;
        $this->view->repo      = $repo;
        $this->view->webhookID = $webhookID;
        $this->view->pager     = $pager;
        $this->view->logs      = empty($logs) ? array() : $logs[$pageID - 1];
        $this->view->orderBy   = $orderBy;
        $this->display();
    }

    /**
     * 启用或关闭 webhook。
     * Enable or disable a webhook.
     *
     * @param  int    $repoID
     * @param  int    $webhookID
     * @param  int    $isEnable
     * @access public
     * @return void
     */
    public function enableWebhook(int $repoID, int $webhookID, int $isEnable)
    {
        $repo = $this->repo->getByID($repoID);

        $webhook          = new stdClass();
        $webhook->enabled = $isEnable == 1;
        $result = $this->loadModel('gitfox')->apiUpdateWebhook((int)$repo->id, $webhookID, $webhook);
        if(!$result) $this->sendError($isEnable == 1 ? $this->lang->repo->webhook->enabledFail : $this->lang->repo->webhook->disabledFail);

        $this->loadModel('action')->create('repo', $repoID, $isEnable == 1 ? 'enablewebhook' : 'disablewebhook');
        return $this->send(array('result' => 'success', 'message' => $isEnable == 1 ? $this->lang->repo->webhook->enabledSuccess : $this->lang->repo->webhook->disabledSuccess, 'load' => true));
    }

    /**
     * 删除 webhook。
     * Delete a webhook.
     *
     * @param  int    $repoID
     * @param  int    $webhookID
     * @access public
     * @return void
     */
    public function deleteWebhook(int $repoID, int $webhookID)
    {
        $repo = $this->repo->getByID($repoID);
        $logs = $this->loadModel('gitfox')->apiGetWebhookExecution((int)$repo->id, $webhookID);
        if(!empty($logs)) $this->sendError($this->lang->repo->webhook->deleteFail);

        $this->gitfox->apiDeleteWebhook((int)$repo->id, $webhookID);

        $this->loadModel('action')->create('repo', $repoID, 'deletewebhook');
        return $this->send(array('result' => 'success', 'message' => $this->lang->repo->webhook->deleteSuccess, 'load' => true));
    }

    /**
     * 查看 webhook 请求数据。
     * View webhook request data.
     *
     * @param  int    $repoID
     * @param  int    $webhookID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function viewWebhookRequest(int $repoID, int $webhookID, int $executionID)
    {
        $repo    = $this->repo->getByID($repoID);
        $execLog = $this->loadModel('gitfox')->apiGetWebhookExecution((int)$repo->id, $webhookID, $executionID);
        if(!$execLog) $execLog = array();

        $this->view->title     = $this->lang->repo->logWebhook;
        $this->view->repoID    = $repoID;
        $this->view->repo      = $repo;
        $this->view->webhookID = $webhookID;
        $this->view->execLog   = $execLog;
        $this->display();
    }

    /**
     * 浏览分支列表。
     * Browse branch list.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $label
     * @param  string $showArchived
     * @param  string $keyword
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseBranch(int $repoID, int $objectID = 0, string $label = 'all', string $showArchived = 'active', string $keyword = '', string $orderBy = 'date_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $repoID = $this->repoZen->processRepoID($repoID, $objectID);
        $this->commonAction($repoID, $objectID);

        $repo = $this->repo->getByID($repoID);

        $keyword = str_replace(' ', '+', urldecode($keyword));
        $keyword = htmlspecialchars(base64_decode($keyword));

        $this->scm->setEngine($repo);
        $branchList = $this->scm->branch($keyword ? $keyword : 'all', $orderBy, $recPerPage, $pageID, $label, $showArchived);
        if(count($branchList) == 0 && $pageID != 1) $this->locate(inLink('browseBranch', "repoID=$repoID&objectID=$objectID&label=$label&showArchived=$showArchived&keyword=$keyword&orderBy=$orderBy&recTotal=0&recPerPage=$recPerPage&pageID=1"));

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $pager->recPerPage = $recPerPage;
        $pager->recTotal = count($branchList) < $pager->recPerPage ? $pager->recPerPage * $pager->pageID : $pager->recPerPage * ($pager->pageID + 1);

        $committers  = $this->loadModel('user')->getCommiters('account');
        $types       = $this->loadModel('repobranchtype')->getBranchTypeList($repoID);
        $rules       = $this->loadModel('repobranchrule')->getBranchRulePairs($repoID, 'branchName', 'repo');
        $currentUser = $this->app->user->account;
        foreach($branchList as &$branch)
        {
            $branch->repoID     = $repoID;
            $branch->branchName = urlencode(helper::safe64Encode($branch->name));
            $branch->objectID   = $objectID;

            $branch->commitID = isset($branch->commit->id) ? $branch->commit->id : '';
            if(isset($branch->commit->sha)) $branch->commitID = $branch->commit->sha;
            $branch->commitID = substr($branch->commitID, 0, 10);

            $branch->committer  = isset($branch->commit->author_name) ? $branch->commit->author_name : '';
            if(isset($branch->commit->author->identity->name)) $branch->committer = $branch->commit->author->identity->name;
            $branch->committer = zget($committers, $branch->committer);

            $branch->commitDate = isset($branch->commit->committed_date) ? date('Y-m-d H:i:s', strtotime($branch->commit->committed_date)) : '';
            if(isset($branch->commit->author->when)) $branch->commitDate = date('Y-m-d H:i:s', strtotime($branch->commit->author->when));

            $separators = array('/', '-', '_', '.');
            $minPos     = false;
            foreach($separators as $separator)
            {
                $pos = strpos($branch->name, $separator);
                if($pos !== false && ($minPos === false || $pos < $minPos)) $minPos = $pos;
            }
            $prefix = ($minPos !== false) ? substr($branch->name, 0, $minPos + 1) : $branch->name;
            $branch->type = '';
            foreach($types as $type)
            {
                if(in_array($prefix, $type->prefixes))
                {
                    $branch->type = $type->name;
                    break;
                }
            }

            if(isset($rules[$branch->name]))
            {
                $branch->rule = $this->lang->repo->branchRuleMode['redefinition'];
            }
            elseif($branch->type)
            {
                $branch->rule = $this->lang->repo->branchRuleMode['inheritance'];
            }
            else
            {
                $branch->rule = '';
            }
            $branch->ahead     = isset($branch->divergence->ahead) ? $branch->divergence->ahead : 0;
            $branch->behind    = isset($branch->divergence->behind) ? $branch->divergence->behind : 0;
            $branch->deletable = !($branch->isDefault || !$this->loadModel('repobranchrule')->checkPrivToDeleteBranch($repoID, $branch->name, $currentUser));
        }
        $branchTypes = $this->loadModel('repobranchtype')->getBranchTypeByRepoID($repo->id, 'id_asc');
        if(!empty($branchTypes))
        {
            $this->lang->repo->featureBar['browsebranch']['all'] = $this->lang->all;
            $branchTypeCount = 0;
            foreach($branchTypes as $branchType)
            {
                $branchTypeCount = $branchTypeCount + 1;
                if($branchTypeCount < 5)
                {
                    $this->lang->repo->featureBar['browsebranch'][$branchType->id] = $branchType->name;
                }
                else
                {

                    if($branchTypeCount == 5 && count($branchTypes) == 5)
                    {
                        $this->lang->repo->featureBar['browsebranch'][$branchType->id] = $branchType->name;
                    }
                    else
                    {
                        if(!isset($this->lang->featureBar['browsebranch']['more'])) $this->lang->repo->featureBar['browsebranch']['more'] = $this->lang->more;
                        $this->lang->repo->moreSelects['browsebranch']['more'][$branchType->id] = $branchType->name;
                    }
                }
            }
        }

        $this->view->title        = $this->lang->repo->browseBranch;
        $this->view->repoID       = $repoID;
        $this->view->objectID     = $objectID;
        $this->view->repo         = $repo;
        $this->view->pager        = $pager;
        $this->view->orderBy      = $orderBy;
        $this->view->branchList   = $branchList;
        $this->view->keyword      = base64_encode($keyword);
        $this->view->users        = $this->user->getPairs('noletter');
        $this->view->label        = $label;
        $this->view->showArchived = $showArchived;
        $this->display();
    }

    /**
     * 通过ajax获取代码库的分支。
     * Ajax: Get branches.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxGetBranchOptions(int $repoID)
    {
        $repo = $this->repo->getByID($repoID);
        $scm  = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $options = $this->getBranchAndTagOptions($this->scm);
        if(!empty($options[0]['items']))
        {
            return print(json_encode($options[0]['items']));
        }
        return print(json_encode(array()));
    }

    /**
     * 通过ajax获取空间的成员列表。
     * Ajax: Get space members.
     *
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function ajaxGetSpaceMembers(int $spaceID)
    {
        $users     = $this->loadModel('user')->getPairs('noletter');
        $spaceUser = $this->loadModel('space')->getSpaceUsers($spaceID);

        $userList = array();
        foreach($spaceUser as $user) $userList[] = array('text' => $users[$user], 'value' => $user);

        return print(json_encode($userList));
    }

    /**
     * addBug
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function addBug(int $repoID)
    {
        $this->loadModel('common')->checkPriv();

        if(!empty($_POST))
        {
            global $config;
            $file  = $this->post->file;
            $v1    = $this->post->fromReversion;
            $v2    = $this->post->revision;
            $begin = $this->post->begin;
            $end   = $this->post->end;
            $v1    = strpos($v1, '^') !== false ? substr($v1, 0, -1) : $v1;
            $v2    = strpos($v2, '^') !== false ? substr($v2, 0, -1) : $v2;
            $bug   = form::data($config->repo->form->addBug)
                ->setIF(!$this->post->entry, 'entry', $file)
                ->add('openedBy', $this->app->user->account)
                ->add('repo', $repoID)
                ->add('lines', $begin . ',' . $end)
                ->add('v1', $v1)
                ->add('v2', $v2)
                ->remove('begin,end,uid,fromReversion,revision,file')
                ->get();
            $bug->type = $bug->repoType;
            $bug = $this->loadModel('file')->processImgURL($bug, 'steps',(string)$this->post->uid);

            $result = $this->repo->saveBug($repoID, $bug);
            if($result['result'] === 'fail')
            {
                return $this->send($result);
            }

            $bugID      = $result['id'];
            $repo       = $this->repo->getById($repoID);
            $file       = $this->repo->decodePath($file);
            $entry      = $repo->name . '/' . $file;
            $location   = sprintf($this->lang->repo->reviewLocation, $entry, substr($v2, 0, 10), $begin, $end);
            $changeFile = $this->repo->encodePath("{$file}#{$begin},{$end}");
            if(empty($v1))
            {
                $link = $this->repo->createLink('view', "repoID=$repoID&objectID=0&entry={$changeFile}&revision=$v2&showBug=1", '', true) . "#L{$begin}";
            }
            else
            {
                $link = $this->repo->createLink('diff', "repoID=$repoID&objectID=0&entry={$changeFile}&oldRevision=$v1&newRevision=$v2&showBug=1", '', true) . "#L{$begin}";
            }

            /* search commit. */
            $commitID = empty($v2) ? $v1 : $v2;
            $this->app->loadClass('pager', true);
            $pager = new pager(0, 1, 1);
            $pager->recPerPage = 1;

            $query = new stdclass();
            $query->commit = $commitID;

            $commits = $this->repo->getCommits($repo, '', '', 'dir', $pager, '', '', $query);
            if(!empty($commits[0]))
            {
                $commit = $commits[0];
                $historyLog = new stdclass();
                if(!empty($commit->author->identity->name))
                {
                    $historyLog->committer = $commit->author->identity->name;
                }elseif(!empty($commit->committer_name))
                {
                    $historyLog->committer = $commit->committer_name;
                }else
                {
                    $historyLog->committer = '';
                }
                /* Record code commit relationship. */
                $historyLog->revision = $commit->revision;
                $historyLog->comment  = $commit->message;
                $historyLog->time     = date("Y-m-d H:i:s", strtotime($commit->time));
                $this->repo->saveCommit($repo->id, array('commits' => [$historyLog]), 0);
                $revisions = $this->dao->select('id')->from(TABLE_REPOHISTORY)
                    ->where('revision')->in($commit->revision)
                    ->andWhere('repo')->eq($repoID)
                    ->fetchPairs('id');
                $this->loadModel('bug')->updateLinkedCommits((int)$bugID, $repoID, $revisions);
            }
            $actionID = $this->loadModel('action')->create('bug', $bugID, 'repoCreated', '', html::a($link, $location, '', "class='iframe'"));
            $this->loadModel('mail')->sendmail($bugID, $actionID);

            return $this->send($result);
        }
    }

    /**
     * 添加评论。
     * Add comment.
     *
     * @access public
     * @return void
     */
    public function addComment()
    {
        if(!empty($_POST))
        {
            $now  = helper::now();
            $bug  = $this->loadModel('bug')->getByID($this->post->objectID);
            $data = fixer::input('post')
                ->add('objectType', 'bug')
                ->add('product', ',' . $bug->product . ',')
                ->add('project', $bug->project)
                ->add('actor', $this->app->user->account)
                ->add('action', 'commented')
                ->add('date', $now)
                ->remove('loadPage')
                ->get();
            if(empty($data->comment)) return $this->sendSuccess(array('message' => '', 'load' => $this->post->loadPage ? $this->post->loadPage : true));

            $this->dao->insert(TABLE_ACTION)->data($data)->exec();
            return $this->sendSuccess(array('message' => '', 'load' => $this->post->loadPage ? $this->post->loadPage : true));
        }
    }

    /**
     * Show review.
     *
     * @param  int    $repoID
     * @param  string $bugList
     * @param  int    $currentBug
     * @access public
     * @return void
     */
    public function ajaxGetBugs($repoID, $bugList, $currentBug = 0)
    {
        $this->loadModel('bug');
        $this->loadModel('file');
        $bugIDList = explode(',', $bugList);
        if(!$currentBug && $bugIDList) $currentBug = $bugIDList[count($bugIDList) - 1];

        $modules  = $this->loadModel('tree')->getAllModulePairs('bug');
        $bugs     = $this->repo->getBugsByRepo($repoID, 'all', 0, $bugIDList);
        $comments = $this->repo->getComments($bugIDList);
        $accounts = array();

        foreach($bugs as $bug)
        {
            $bug->files      = array();
            $bug->actions    = array();
            $bug->toCases    = array();
            $bug->moduleName = zget($modules, $bug->module, '');
            $bug = $this->file->replaceImgURL($bug, 'steps');

            $accounts[] = $bug->openedBy;
        }

        $this->view->repoID     = $repoID;
        $this->view->bugs       = $bugs;
        $this->view->bugIDList  = $bugIDList;
        $this->view->comments   = $comments;
        $this->view->currentBug = $currentBug;
        $this->view->users      = $this->loadModel('user')->getListByAccounts($accounts, 'account');
        $this->view->commentUrl = $this->repo->createLink('addComment');
        $this->display();
    }

    /**
     * Ajax get committer.
     *
     * @param  int    $repoID
     * @param  string $entry
     * @param  int    $revision
     * @param  int    $line
     * @access public
     * @return void
     */
    public function ajaxGetCommitter($repoID, $entry, $revision, $line)
    {
        if($this->get->repoPath) $entry = $this->get->repoPath;
        $repo  = $this->repo->getRepoByID($repoID);
        $entry = $this->repo->decodePath($entry);

        $this->scm->setEngine($repo);
        $blames   = $this->scm->blame($entry, $revision);
        $committer = '';
        while($line > 0)
        {
            if(isset($blames[$line]['committer']))
            {
                $committer = $blames[$line]['committer'];
                break;
            }
            $line--;
        }
        echo $committer;
    }

    /**
     * Delete bug.
     *
     * @param  int    $bugID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteBug($bugID, $confirm = 'no')
    {
        if($confirm == 'yes')
        {
            $this->loadModel('bug')->delete(TABLE_BUG, $bugID);
            echo 'deleted';
        }
        return false;
    }

    /**
     * Delete comment.
     *
     * @param  int    $commentID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteComment($commentID, $confirm = 'no')
    {
        if($confirm == 'yes')
        {
            $result = $this->repo->deleteComment($commentID);
            if($result) echo 'deleted';
        }
        return false;
    }

    /**
     * 编辑评论。
     * Edit comment.
     *
     * @param  int    $commentID
     * @access public
     * @return void
     */
    public function editComment($commentID)
    {
        if(!empty($_POST))
        {
            $comment = $this->loadModel('file')->pasteImage($this->post->commentText);
            $this->repo->updateComment($commentID, $comment);
            return $this->sendSuccess(array('message' => '', 'load' => $this->post->loadPage ? $this->post->loadPage : true));
        }
    }

    /**
     * Show review.
     *
     * @param  int    $repoID
     * @param  string $browseType
     * @param  int    $objectID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function review(int $repoID, string $browseType = '', int $objectID = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save the original $repoID to this variable to check if $repoID is 0. */
        $isAllRepo = !$repoID;

        if($repoID == 0) $repoID = $this->repo->saveState($repoID);
        $this->commonAction($repoID, (int)$objectID);

        $firstOpen  = empty($browseType);
        $browseType = strtolower($browseType ? $browseType : 'assigntome');
        $this->app->loadLang('bug');
        $this->repoZen->setBackSession('list', true);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $bugs = $this->repo->getBugsByRepo($objectID && $isAllRepo ? 0 : $repoID, $browseType, $objectID, array(), $orderBy, $pager);
        if($firstOpen && empty($bugs)) return $this->locate(inLink('review', "repoID={$repoID}&browseType=all"));

        $repo = $this->repo->getById($repoID);
        $revisions = array();
        foreach($bugs as $bug)
        {
            $revisions[] = $bug->v2;
            if(!empty($bug->v1)) $revisions[] = $bug->v1;
        }
        $this->view->historys = $this->dao->select('revision,commit')->from(TABLE_REPOHISTORY)->where('revision')->in($revisions)->andWhere('repo')->eq($repoID)->fetchPairs('revision', 'commit');

        if($this->app->tab == 'execution') $this->view->executionID = $objectID;

        $repoList  = $this->loadModel('repo')->getList($objectID);
        $repoPairs = array();
        foreach($repoList as $repo)
        {
            $repoPairs[$repo->id] = $repo->name;
        }

        foreach($bugs as $bug) $bug->type = $bug->repoType ? $bug->repoType : $bug->type;

        $this->view->allRepo    = $isAllRepo;
        $this->view->repoPairs  = $repoPairs;
        $this->view->repos      = $this->repo->getList($objectID);
        $this->view->repoGroup  = $this->repo->getRepoGroup($this->app->tab);
        $this->view->orderBy    = $orderBy;
        $this->view->repoID     = $repoID;
        $this->view->objectID   = $objectID;
        $this->view->repo       = $repo;
        $this->view->bugs       = $bugs;
        $this->view->pager      = $pager;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->title      = $this->lang->repo->review;
        $this->view->browseType = $browseType;
        $this->display();
    }

    /**
     * 浏览分支列表。
     * Browse branch list.
     *
     * @param  int    $space
     * @param  string $type
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $keyword
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseSystem(int $space = 0, string $type = 'all',string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, int $param = 0)
    {
        $this->app->loadClass('pager', true);
        $this->loadModel('space')->setMenu($space);

        $queryID   = $type == 'bySearch' ? $param : 0;
        $actionURL = $this->createLink('repo', 'browseSystem', "space={$space}&type=bySearch&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}&param=myQueryID");
        $this->repo->buildSystemSearchForm($queryID, $actionURL);

        $systemQuery = $type == 'bySearch' ? $this->repoZen->getSystemSearchQuery($queryID) : '';
        $systems = $this->repo->getSystemList($systemQuery, $space);
        foreach($systems as &$system)
        {
            $system->latestRelease = $system->latestRelease ? $system->latestRelease : '';
        }
        list($order, $sort) = explode('_', $orderBy);
        $orderList = array();
        foreach($systems as $orderSystem)
        {
            $orderList[] = $orderSystem->$order;
        }
        if($orderList) array_multisort($orderList, $sort == 'desc' ? SORT_DESC : SORT_ASC, $systems);

        /* Pager. */
        $this->app->loadClass('pager', true);
        $systemTotal = count($systems);
        $pager       = new pager($systemTotal, $recPerPage, $pageID);
        $systems     = array_chunk($systems, (int)$pager->recPerPage);
        if($systems && !isset($systems[$pageID - 1])) $pageID = 1;

        $this->view->title    = $this->lang->repo->browseSystem;
        $this->view->appList  = empty($systems) ? $systems: $systems[$pageID - 1];
        $this->view->releases = $this->loadModel('release')->getPairs();
        $this->view->products = $this->loadModel('product')->getPairs('all', 0, '', 'all');
        $this->view->orderBy  = $orderBy;
        $this->view->pager    = $pager;
        $this->view->param    = $param;
        $this->view->type     = $type;
        $this->view->inSpace  = !empty($space);
        $this->view->spaceID  = $space;
        $this->display();
    }

    /**
     * 创建代码库分支。
     * Create code repo branch.
     *
     * @param  int $objectID
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function createRepoBranch(int $objectID, int $repoID)
    {
        $repoPairs = array();
        if($objectID != 0)
        {
            $repoGroup = $this->repo->getRepoGroup('project', $objectID,  $this->config->repo->notSyncSCM);
            if($repoGroup)
            {
                foreach($repoGroup as $groups)
                {
                    if(empty($groups['items'])) continue;
                    foreach ($groups['items'] as $groupItem) $repoPairs[$groupItem['id']] = $groupItem['text'];
                }
            }
        }
        else
        {
            $repoInfo = $this->repo->fetchByID($repoID);
            $repoPairs[$repoID] = $repoInfo->name;
        }

        $repoList  = $this->repo->getListByPriv('haspriv');
        $this->scm->setEngine($repoList[$repoID]);
        if(!empty($_POST))
        {
            $branch = form::data($this->config->repo->form->createBranch)->get();
            if(mb_strlen($branch->branchName, 'UTF-8') > 30)
            {
                return $this->sendError(array('branchName' => $this->lang->repo->error->branchNameTooLong));
            }
            $repoID = $branch->codeRepo ? $branch->codeRepo : $repoID;

            if(!$this->loadModel('repobranchrule')->checkPrivToCreateBranch($repoID, $branch->branchName, $this->app->user->account))
            {
                return $this->send(array('result' => 'fail', 'message' => $this->lang->repo->notice->noPermissionToCreateBranch));
            }

            $this->scm->setEngine($repoList[$repoID]);

            $this->scm->createBranch($branch->branchName, $branch->branchFrom);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->repo->saveRelation($repoID, $branch->branchName, $repoID, 'repo');
            $this->loadModel('action')->create('repo', $repoID, 'createRepoBranch', '', $branch->branchName);

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $repoID));
            $link = $this->repo->createLink('browsebranch', "repoID={$repoID}&objectID={$objectID}", '', false);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
        }

        $this->view->repoID         = $repoID;
        $this->view->objectID       = $objectID;
        $this->view->objectType     = 'project';
        $this->view->repoPairs      = $repoPairs;
        $this->view->canCreate      = true;
        $this->view->fromList       = $this->getBranchAndTagOptions($this->scm);
        $this->view->linkedBranches = array();
        $this->display();
    }

    /**
     * 创建标签。
     * Create a tag.
     *
     * @param  int    $objectID
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function createTag(int $objectID, int $repoID = 0)
    {
        $repoGroup = $this->repo->getRepoGroup('project', $objectID);
        $repoPairs = [];
        if($repoGroup)
        {
            foreach($repoGroup as $groups)
            {
                if(empty($groups['items'])) continue;
                foreach ($groups['items'] as $groupItem) $repoPairs[$groupItem['id']] = $groupItem['text'];
            }
        }
        $repoList  = $this->repo->getListByPriv('haspriv');
        $this->scm->setEngine($repoList[$repoID]);
        if(!empty($_POST))
        {
            $tag    = form::data($this->config->repo->form->createTag)->get();
            $repoID = $tag->codeRepo ? $tag->codeRepo : $repoID;
            $this->scm->setEngine($repoList[$repoID]);

            $result = $this->scm->createTag($repoList[$repoID]->id, $tag->tagName, $tag->tagFrom, $tag->comment);

            if(dao::isError()) return $this->sendError($this->lang->repo->error->createdFail . ': ' .  $this->parseErrorContent(dao::getError()['apiMessage']));
            if(empty($result)) return $this->sendError($this->lang->repo->error->createdFail);

            $this->repo->saveRelation($repoID, $tag->tagName, $repoID, 'repo', 'linkrepotag');
            $this->loadModel('action')->create('repo', $repoID, 'createRepoTag', '', $tag->tagName);

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $repoID));
            $link = $this->repo->createLink('browsetag', "repoID={$repoID}&objectID={$objectID}", '', false);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        list($branchID, $branches, $tags) = $this->setBranchTag($repoList[$repoID], '');
        unset($branches, $tags);

        $branchID = helper::safe64Encode(base64_encode($branchID));
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 1, 1);

        $pager->recPerPage      = 1;
        $this->view->repoID     = $repoID;
        $this->view->objectType = 'project';
        $this->view->repoPairs  = $repoPairs;
        $this->view->canCreate  = common::hasPriv('repo', 'createTag');
        $this->view->branchID   = $branchID;
        $this->view->objectID   = $objectID;

        $tagFrom = $this->getBranchAndTagOptions($this->scm);
        if(isset($tagFrom[1])) unset($tagFrom[1]); /* Remove the tags. */
        $this->view->fromList = $tagFrom;

        $commits = $this->repo->getCommits($repoList[$repoID], '', $branchID, 'dir', $pager, '', '', null);
        $commit  = new stdClass();
        if(!empty($commits))
        {
            $commits = current($commits);
            $commit->commitID = $commits->revision;
            $commit->shortID  = substr($commits->revision, 0, 10);
            $commit->message  = $commits->message;
        }
        $this->view->commit = $commit;
        $this->display();
    }

    /**
     * 删除指定分支。
     * Delete specified branch.
     *
     * @param  int $repoID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function deleteBranch(int $repoID, string $branch)
    {
        $branch = helper::safe64Decode($branch);

        $this->loadModel('repobranchrule');
        if(!$this->repobranchrule->checkPrivToDeleteBranch($repoID, $branch, $this->app->user->account))
        {
            return $this->send(array('result' => 'fail', 'message' => $this->lang->repo->notice->noPermissionToDeleteBranch));
        }

        $this->loadModel('gitfox')->apiDeleteBranch($repoID, $branch);
        if(dao::isError()) return $this->sendError($this->parseErrorContent(dao::getError()));
        $this->repo->unlinkObjectBranch(0, '', $repoID, $branch);
        if(dao::isError()) return $this->sendError(dao::getError());
        $this->loadModel('action')->create('repobranch', $repoID, 'deleted', '', $branch);

        $branchRule = $this->repobranchrule->getBranchRule(0, $repoID, $branch);
        if(!empty($branchRule))
        {
             $this->repobranchrule->deleteBranchRule($branchRule->id);
        }

        return $this->sendSuccess(array('load' => true, 'message' => $this->lang->deleteSuccess));
    }

    /**
     * 删除指定tag。
     * Delete specified tag.
     *
     * @param  int    $repoID
     * @param  string $tag
     * @access public
     * @return void
     */
    public function deleteTag(int $repoID, string $tag)
    {
        $tag  = helper::safe64Decode($tag);
        $this->loadModel('gitfox')->apiDeleteTag($repoID, $tag);
        $this->loadModel('action')->create('repotag', $repoID, 'deleted', '', $tag);

        if(dao::isError()) return $this->sendError(dao::getError());
        return $this->sendSuccess(array('load' => true, 'message' => $this->lang->deleteSuccess));
    }

    /**
     * 获取分支下提交记录。
     * Commit records of branch.
     *
     * @param  int    $repoID
     * @param  string $branchID
     * @param  string $search
     * @access public
     * @return object
     */
    public function ajaxGetBranchCommits(int $repoID, string $branchID, string $search = '')
    {
        $repo = $this->repo->getByID($repoID);
        list($branchID) = $this->repoZen->setBranchTag($repo, $branchID);
        $branchID = helper::safe64Encode(base64_encode($branchID));
        $pageSize = 10;

        /* set pager */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, $pageSize, 1);
        $pager->recPerPage = $pageSize;

        /* set query */
        $query = new stdClass();
        $query->begin = '';
        $query->end = '';
        $query->committer = '';
        $query->commit = !empty($search) ? $search : '';
        $commits    = $this->repo->getCommits($repo, '', $branchID, 'dir', $pager, '', '', $query);

        /* packaging commits */
        $retCommits = array();
        if(!empty($commits))
        {
            foreach ($commits as $item)
            {
                $shortID      = substr($item->revision, 0, 10);
                $commitDate   = isset($item->committed_date) ? date('Y-m-d H:i:s', strtotime($item->committed_date)) : '';
                $commitDetail = sprintf($this->lang->repo->commitDetail, "{$shortID} {$item->message}", $commitDate, $item->committer);
                array_push($retCommits, [
                    'text'  => array('html' => "<div class='tagCommit font-mono' title='{$commitDetail}'><div class='commit'>" . $shortID . "</div><div class='message'>" . $item->message . "</div></div>"),
                    'value' => $item->revision,
                    'key'   => $shortID,
                ] );
            }
        }
        echo json_encode($retCommits);
    }

    /**
     * 显示导入进度。
     * Ajax show import progress.
     *
     * @param  int $repoID
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function ajaxShowImportProgress(int $repoID, int $spaceID = 0)
    {
        $this->view->title   = $this->lang->repo->showImportProgress;
        $this->view->repoID  = $repoID;
        $this->view->spaceID = $spaceID;
        $this->display();
    }

    /**
     * 获取导入进度。
     * Ajax get import progress.
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function ajaxGetImportProgress(int $repoID)
    {
        $result = $this->loadModel('gitfox')->request("/repos/import-progress", 'GET', array('repoID' => $repoID));
        if(!dao::isError() && !empty($result->status) && $result->status == 'finished')
        {
            $repo = $this->repo->getByID($repoID);
            if(empty($repo) || $repo->synced) return print(json_encode(array('result' => 'success', 'data' => $result)));

            if($this->repo->isSvn($repo))
            {
                $this->repo->markSynced($repo->id);
                return print(json_encode(array('result' => 'success', 'data' => $result)));
            }

            $this->commonAction($repoID);
            $this->scm->setEngine($repo);

            $branchID = (string)$this->cookie->syncBranch;
            if(!$this->cookie->syncBranch && !$this->repo->isSvn($repo))
            {
                $branches = $this->scm->branch();
                if(empty($branches)) return print($this->lang->repo->error->empty);

                $branchID = current($branches);
            }

            $branches = $this->repoZen->getSyncBranches($branchID);

            $logs    = array();
            $version = 1;

            $commitCount = $this->repo->saveCommit($repoID, $logs, $version, $branchID);
            $this->repoZen->checkSyncResult($repo, $branches, $branchID, $commitCount, 'batch');
        }
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        echo json_encode(array('result' => 'success', 'data' => $result));
    }

    /**
     * 显示导入结果。
     * Ajax show import result.
     *
     * @param  int $repoID
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function ajaxShowImportResult(int $repoID, int $spaceID = 0)
    {
        $result = $this->loadModel('gitfox')->request("/repos/import-progress", 'GET', array('repoID' => $repoID));

        $message = '';
        if(dao::isError() || empty($result->status) || $result->status != 'finished')
        {
            if(empty($result)) $message = $this->lang->repo->importProgress->importFailed;
            if(dao::isError())
            {
                $error   = dao::getError();
                $message = isset($error['apiMessage']) ? sprintf($this->lang->repo->importProgress->failMessage, $error['apiMessage']) : $error;
            }
            if(!empty($result->failure)) $message = sprintf($this->lang->repo->importProgress->failMessage, $result->failure);
        }
        else
        {
            $this->loadModel('action')->create('repo', $repoID, 'imported');
        }

        $this->view->title   = $this->lang->repo->showImportResult;
        $this->view->message = $message;
        $this->view->repoID  = $repoID;
        $this->view->spaceID = $spaceID;
        $this->display();
    }
}
