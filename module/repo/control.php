<?php
declare(strict_types=1);
/**
 * The control file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
        session_write_close();
    }

    /**
     * Common actions.
     *
     * @param  int    $repoID
     * @param  int    $objectID     projectID|executionID
     * @access public
     * @return void
     */
    public function commonAction(int $repoID = 0, int $objectID = 0)
    {
        $fromModal = in_array($this->app->rawModule, array('git', 'svn'));
        $tab       = $fromModal ? '' :$this->app->tab;
        $this->repos = $this->repo->getRepoPairs($tab, $objectID);

        if($tab == 'project')
        {
            $project = $this->loadModel('project')->getByID($objectID);
            if($project && $project->model === 'kanban') return $this->locate($this->createLink('project', 'index', "projectID=$objectID"));

            $this->loadModel('project')->setMenu($objectID);
            $this->view->projectID = $objectID;
        }
        elseif($tab == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($objectID);
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
            $this->repo->setMenu($this->repos, $repoID);
        }

        if(empty($this->repos) && !in_array(strtolower($this->methodName), array('create', 'setrules', 'createrepo', 'import', 'maintain')))
        {
            $method = $this->app->tab == 'devops' ? 'maintain' : 'create';
            if($this->config->inCompose && $method == 'create') $method = 'createRepo';
            return $this->locate(inLink($method, "objectID=$objectID"));
        }
        $this->view->fromModal = $fromModal;
    }

    /**
     * 版本库列表。
     * List all repo.
     *
     * @param  int    $objectID
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $type
     * @param  int    $param
     * @access public
     * @return void
     */
    public function maintain(int $objectID = 0, string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1, string $type = '', int $param = 0)
    {
        $repoID = $this->repo->saveState(0, $objectID);
        if($this->viewType !== 'json') $this->commonAction($repoID, $objectID);

        $repoList = $this->repo->getList(0, '', $orderBy, null, false, true, $type, $param);
        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal = count($repoList);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $repoList = array_chunk($repoList, $pager->recPerPage);

        if($repoList && !isset($repoList[$pageID - 1])) $pageID = 1;
        $repoList = empty($repoList) ? array() : $repoList[$pageID - 1];

        /* Get success jobs of sonarqube.*/
        $sonarRepoList = $this->loadModel('job')->getSonarqubeByRepo(helper::arrayColumn($repoList, 'id'));
        $successJobs   = $this->loadModel('compile')->getSuccessJobs(helper::arrayColumn($sonarRepoList, 'id'));

        $products = $this->loadModel('product')->getPairs('all', 0, '', 'all');
        $projects = $this->loadModel('project')->getPairs();

        $this->repoZen->buildRepoSearchForm($products, $projects, $objectID, $orderBy, $recPerPage, $pageID, $param);

        $this->view->title         = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->browse;
        $this->view->serverPairs   = $this->loadModel('pipeline')->getPairs('gitlab');
        $this->view->type          = $type;
        $this->view->orderBy       = $orderBy;
        $this->view->objectID      = $objectID;
        $this->view->pager         = $pager;
        $this->view->repoList      = $repoList;
        $this->view->products      = $products;
        $this->view->projects      = $projects;
        $this->view->sonarRepoList = $sonarRepoList;
        $this->view->successJobs   = $successJobs;
        $this->view->repoServers   = $this->pipeline->getPairs($this->config->pipeline->checkRepoServers);

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
            $isPipelineServer = in_array(strtolower($this->post->SCM), $this->config->repo->gitServiceList) ? true : false;
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
    }

    /**
     * 创建版本库，同步创建远程版本库。
     * Create a repo.
     *
     * @param  int    $objectID  projectID|executionID
     * @access public
     * @return void
     */
    public function createRepo(int $objectID = 0)
    {
        if($_POST)
        {
            /* Prepare data. */
            $formData = form::data($this->config->repo->form->createRepo)->get();
            $repo     = $this->repoZen->prepareCreateRepo($formData);

            /* Create a repo. */
            if($repo) $repoID = $this->repo->createRepo($repo);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(in_array($formData->SCM, $this->config->repo->notSyncSCM))
            {
                /* Add webhook. */
                $repo = $this->repo->getByID($repoID);
                $this->loadModel($formData->SCM)->updateCodePath($repo->serviceHost, (int)$repo->serviceProject, (int)$repo->id);
                $this->repo->updateCommitDate($repoID);
            }

            $this->loadModel('action')->create('repo', $repoID, 'created');

            $link = $this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
        }

        $this->commonAction(0, $objectID);
        $this->repoZen->buildCreateRepoForm($objectID);
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
        $object     = $this->loadModel($objectType)->fetchByID($objectID);
        $productIds = array(zget($object, 'product', 0));
        if($objectType == 'task') $productIds = $this->loadModel('product')->getProductIDByProject($object->execution, false);

        $repoList  = $this->repo->getListBySCM(implode(',', $this->config->repo->gitServiceTypeList), 'haspriv');
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
            $result = $this->scm->createBranch($branch->branchName, $branch->branchFrom);
            if($result['result'] == 'fail') return $this->sendError($this->lang->repo->error->createdFail . ': ' . $this->repoZen->parseErrorContent($result['message']));

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
    public function edit(int $repoID, int $objectID = 0)
    {
        $this->commonAction($repoID, $objectID);

        if($_POST)
        {
            $repo = $this->repo->getByID($repoID);

            /* Prepare data. */
            $formData         = form::data($this->config->repo->form->edit);
            $isPipelineServer = in_array(strtolower($this->post->SCM), $this->config->repo->gitServiceList) ? true : false;
            $editData         = $this->repoZen->prepareEdit($formData, $repo, $isPipelineServer);

            if($editData) $noNeedSync = $this->repo->update($editData, $repo, $isPipelineServer);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $newRepo  = $this->repo->getByID($repoID);
            $actionID = $this->loadModel('action')->create('repo', $repoID, 'edited');
            $changes  = common::createChanges($repo, $newRepo);
            $this->action->logHistory($actionID, $changes);

            if(!$noNeedSync)
            {
                $link = $this->repo->createLink('showSyncCommit', "repoID=$repoID");
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('maintain')));
        }

        $this->repoZen->buildEditForm($repoID, $objectID);
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

        $this->repo->deleteRepo($repoID);
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
        if($repo->SCM == 'Gitlab') $repo = $this->repo->processGitService($repo, true);

        $dropMenus = array();
        if(in_array($repo->SCM, $this->config->repo->gitTypeList)) $dropMenus = $this->repoZen->getBranchAndTagItems($repo, $this->cookie->repoBranch);

        if($this->app->tab == 'execution') $this->view->executionID = $objectID;
        $this->view->title     = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->view;
        $this->view->dropMenus = $dropMenus;
        $this->view->type      = 'view';
        $this->view->branchID  = $this->cookie->repoBranch;
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
        $this->commonAction($repoID, $objectID);
        $this->repoZen->setBrowseSession();

        /* Get repo and synchronous commit. */
        $repo = $this->repo->getByID($repoID);
        if($repo->SCM == 'Git' && !is_dir($repo->path)) return $this->sendError(sprintf($this->lang->repo->error->notFound, $repo->name, $repo->path), $this->repo->createLink('maintain'));
        if($this->repoZen->checkRepoInternet($repo)) return $this->sendError($this->lang->repo->error->connect, true);
        if(!$repo->synced) return $this->locate($this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID"));

        /* Set branch or tag for git. */
        $branchID = $branchID ? base64_decode(helper::safe64Decode($branchID)) : '';
        list($branchID, $branches, $tags) = $this->repoZen->setBranchTag($repo, $branchID);
        if($this->app->tab == 'devops' && $repo->SCM != 'Subversion' && empty($branches)) return $this->sendError($this->lang->repo->error->empty, true);

        /* Refresh repo. */
        $refresh = $refresh || $this->cookie->repoRefresh;
        if($refresh)
        {
            helper::setcookie('repoRefresh', 0);
            $this->repo->updateCommit($repoID, $objectID, $branchID);
            if($repo->SCM == 'Gitlab') $this->repo->checkDeletedBranches($repoID, $branches);
        }

        /* Get revisions. */
        $this->app->loadClass('pager', true);
        $pager        = new pager($recTotal, $recPerPage, $pageID);
        $revisions    = $this->repoZen->getCommits($repo, $path, $revision, $type, $pager, $objectID);
        $lastRevision = empty($revisions) ? new stdclass() : current($revisions);

        if($path == '') $this->repoZen->updateLastCommit($repo, $lastRevision);

        /* Get files info. */
        $base64BranchID = helper::safe64Encode(base64_encode($branchID));
        $infos          = $this->repoZen->getFilesInfo($repo, $path, $branchID, $base64BranchID, $objectID);

        /* Synchronous commit only in root path. */
        if(in_array($repo->SCM, $this->config->repo->gitTypeList) && $repo->SCM != 'Gitlab' && empty($path) && $infos && empty($revisions)) $this->locate($this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID&branch=" . helper::safe64Encode(base64_encode($this->cookie->repoBranch))));

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
        if($this->app->tab == 'devops' && $repo->SCM != 'Subversion' && empty($branches)) return $this->sendError($this->lang->repo->error->empty, true);

        /* Build the search form. */
        $browseType = strtolower($browseType);
        $queryID    = $browseType == 'bysearch' ? $param : 0;
        $branchID   = helper::safe64Encode(base64_encode($branchID));
        $actionURL  = $this->createLink('repo', 'log', "repoID={$repoID}&branchID={$branchID}&objectID={$objectID}&entry=&source={$source}&browseType=bySearch&param=myQueryID");
        $this->repoZen->buildSearchForm($queryID, $actionURL);

        $this->commonAction($repoID, $objectID);
        $query = $browseType == 'bysearch' ? $this->repoZen->getSearchForm($queryID, !in_array($repo->SCM, $this->config->repo->notSyncSCM)) : null;
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
        if($revision)
        {
            if(in_array($repo->SCM, $this->config->repo->gitTypeList))
            {
                $thisAndPrevRevisions = $this->scm->exec("rev-list -n 2 {$revision} --");

                array_shift($thisAndPrevRevisions);
                if($thisAndPrevRevisions) $oldRevision = array_shift($thisAndPrevRevisions);
            }
            else
            {
                $oldRevision = $this->repo->getHistoryRevision($repoID, $revision, false, 'lt');
            }
        }

        if(empty($oldRevision))
        {
            $oldRevision = '^';
            if($revision && in_array($repo->SCM, $this->config->repo->gitTypeList)) $oldRevision = "{$revision}^";
        }

        $this->locate($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=&oldrevision=$oldRevision&newRevision={$log[0]->revision}"));
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
        $encoding  = empty($encoding) ? $repo->encoding : $encoding;
        $encoding  = strtolower(str_replace('_', '-', $encoding));
        $blames    = $this->scm->blame($entry, $revision);
        $revisions = array();
        foreach($blames as $i => $blame)
        {
            if(isset($blame['revision'])) $revisions[$blame['revision']] = $blame['revision'];
            if($encoding != 'utf-8') $blames[$i]['content'] = helper::convertEncoding($blame['content'], $encoding);
        }

        $log = in_array($repo->SCM, $this->config->repo->gitTypeList) ? $this->repo->getHistoryRevision($repo->id, $revision, true) : '';

        $this->view->title        = $this->lang->repo->common;
        $this->view->repoID       = $repoID;
        $this->view->branchID     = (string)$this->cookie->repoBranch;
        $this->view->objectID     = $objectID;
        $this->view->repo         = $repo;
        $this->view->revision     = $revision;
        $this->view->entry        = $entry;
        $this->view->file         = $file;
        $this->view->encoding     = str_replace('-', '_', $encoding);
        $this->view->revisionName = ($log && in_array($repo->SCM, $this->config->repo->gitTypeList)) ? $this->repo->getGitRevisionName($log->revision, $log->commit) : $revision;
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
     * @param  bool   $isBranchOrTag
     * @access public
     * @return void
     */
    public function diff(int $repoID, int $objectID = 0, string $entry = '', string $oldRevision = '', string $newRevision = '', int $showBug = 0, string $encoding = '', int $isBranchOrTag = 0)
    {
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

        $this->config->repo->notSyncSCM[] = 'Subversion';
        if(!in_array($repo->SCM, $this->config->repo->notSyncSCM) && !is_dir($repo->path)) return $this->sendError(sprintf($this->lang->repo->error->notFound, $repo->name, $repo->path), $this->repo->createLink('maintain'));

        $arrange = $this->cookie->arrange ? $this->cookie->arrange : 'inline';
        if($this->server->request_method == 'POST') return $this->repoZen->locateDiffPage($repoID, $objectID, $arrange, $isBranchOrTag, $file);

        $diffs    = array();
        $encoding = empty($encoding) ? $repo->encoding : $encoding;
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

        if($isBranchOrTag)
        {
            $fromRevision = urldecode(helper::safe64Decode($fromRevision));
            $toRevision   = urldecode(helper::safe64Decode($toRevision));
        }

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
        $queryID    = $browseType == 'bySearch' ? (int)$param : 0;

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
     * @param  int    $serverID
     * @access public
     * @return void
     */
    public function import(int $serverID = 0)
    {
        if($this->viewType !== 'json') $this->commonAction();

        $serverList = $this->loadModel('pipeline')->getPairs(implode(',', $this->config->repo->notSyncSCM), true);
        if(!$serverID) $serverID = key($serverList);

        if($_POST)
        {
            if($this->post->product)
            {
                $repos = form::batchData($this->config->repo->form->import)->get();

                if($repos) $this->repo->batchCreate($repos, $serverID, (string)$this->post->serverType);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->repo->createLink('maintain')));
        }

        $server      = $this->pipeline->getByID($serverID);
        $hiddenRepos = $this->loadModel('setting')->getItem('owner=system&module=repo&section=hiddenRepo&key=' . $serverID);

        $linkUser = $server ? $this->pipeline->getOpenIdByAccount($server->id, $server->type, $this->app->user->account) : array();
        $repoList = $server && ($linkUser || $this->app->user->admin) ? $this->repoZen->getNotExistRepos($server) : array();
        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');

        $this->view->title       = $this->lang->repo->common . $this->lang->hyphen . $this->lang->repo->importAction;
        $this->view->servers     = $serverList;
        $this->view->products    = $products;
        $this->view->server      = $server;
        $this->view->repoList    = array_values($repoList);
        $this->view->hiddenRepos = explode(',', $hiddenRepos);
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
     * @access public
     * @return void
     */
    public function ajaxGetDiffEditorContent(int $repoID, int $objectID = 0, string $entry = '', string $oldRevision = '', string $newRevision = '', int $showBug = 0, string $encoding = '')
    {
        if(!$entry) $entry = (string) $this->cookie->repoCodePath;

        $file      = $entry;
        $repo      = $this->repo->getByID($repoID);
        $entry     = urldecode($this->repo->decodePath($entry));
        $revision  = str_replace('*', '-', $oldRevision);
        $nRevision = str_replace('*', '-', $newRevision);

        $entry    = urldecode($entry);
        $pathInfo = pathinfo($entry);
        $encoding = empty($encoding) ? $repo->encoding : $encoding;
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
     * @access public
     * @return void
     */
    public function ajaxGetEditorContent(int $repoID, int $objectID = 0, string $entry = '', string $revision = 'HEAD', int $showBug = 0, string $encoding = '')
    {
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
        $encoding = empty($encoding) ? $repo->encoding : $encoding;
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
        $this->view->revision    = $revision;
        $this->view->oldRevision = '';
        $this->view->file        = $file;
        $this->view->lines       = $lines;
        $this->view->entry       = $entry;
        $this->view->suffix      = $suffix;
        $this->view->content     = $content ? $content : '';
        $this->view->pathInfo    = $pathInfo;
        $this->view->objectID    = $objectID;
        $this->view->showEditor  = (strpos($this->config->repo->images, "|$suffix|") === false and $suffix != 'binary') ? true : false;
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

        if(in_array($repo->SCM, array('Gitea', 'Gogs')))
        {
            $syncLog = $this->repoZen->syncLocalCommit($repo);
            if($syncLog) return print(trim($syncLog));
        }

        $this->commonAction($repoID);
        $this->scm->setEngine($repo);

        $branchID = $repo->SCM == 'Subversion' ? '' : (string)$this->cookie->syncBranch;
        if(in_array($repo->SCM, $this->config->repo->gitTypeList) && !$this->cookie->syncBranch)
        {
            $branches = $this->scm->branch();
            if(empty($branches)) return print($this->lang->repo->error->empty);

            $branchID = current($branches);
        }

        $branches = $this->repoZen->getSyncBranches($repo, $branchID);

        $logs    = array();
        $version = 1;
        if(!in_array($repo->SCM, $this->config->repo->notSyncSCM))
        {
            $latestInDB = $this->repo->getLatestCommit($repoID, false);

            $version  = empty($latestInDB) ? 1 : $latestInDB->commit + 1;
            if(in_array($repo->SCM, array('Git', 'Gitea', 'Gogs')))
            {
                $revision = $version == 1 ? 'HEAD' : $latestInDB->commit;
            }
            else
            {
                $revision = $version == 1 ? '0' : $latestInDB->revision;
            }
            $batchNum = $type == 'batch' ? $this->config->repo->batchNum : 0;
            $logs     = $this->scm->getCommits($revision, $batchNum, $branchID);
        }

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
    public function ajaxSyncBranchCommit(int $repoID = 0, string $branch = '')
    {
        set_time_limit(0);
        $repo = $this->repo->getByID($repoID);
        if(empty($repo)) return;
        if(!in_array($repo->SCM, $this->config->repo->gitTypeList)) return print('finish');
        if($branch) $branch = base64_decode(helper::safe64Decode($branch));

        $this->scm->setEngine($repo);

        $this->repoZen->setRepoBranch($branch);
        helper::setcookie("syncBranch", $branch);

        $latestInDB = $this->dao->select('t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF(in_array($repo->SCM, $this->config->repo->gitTypeList) and $this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->orderBy('t1.time')
            ->limit(1)
            ->fetch();

        $version  = empty($latestInDB) ? 1 : $latestInDB->commit + 1;
        $logs     = array();
        $revision = $version == 1 ? 'HEAD' : $latestInDB->commit;
        if($repo->SCM == 'Gitlab' and $version > 1) $revision = $latestInDB->revision;

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
        if($repo->SCM != 'Subversion') return print(json_encode(array()));

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
        if($module == 'repo' and !in_array($method, array('review', 'diff', 'browsetag', 'browsebranch', 'log'))) $method = 'browse';
        if($module == 'mr' && $method != 'create')  $method = 'browse';
        if($module == 'job') $method = 'browse';
        if($module == 'compile' and $method == 'logs') $method = 'browse';
        if($module == 'bug' and $method == 'view')
        {
            $module = 'repo';
            $method = 'review';
        }

        $params = '';
        if($projectID)
        {
            if($method == 'browse' || $method == 'log') $params = "&branchID=&objectID=$projectID";
            if(in_array($method, array('browsetag', 'browsebranch'))) $params = "&objectID=$projectID";
        }

        /* Get repo group by type. */
        $repoGroup = $this->repo->getRepoGroup('project', $projectID);

        $this->view->repoID    = $repoID;
        $this->view->repoGroup = $repoGroup;
        $this->view->link      = $this->createLink($module, $method, "repoID=%s" . $params);

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
     * 获取服务器下拉列表数据。
     * Ajax get hosts.
     *
     * @param  string    $scm
     * @access public
     * @return void
     */
    public function ajaxGetHosts(string $scm)
    {
        $hosts = $this->loadModel('pipeline')->getPairs($scm, true);

        $options = array();
        foreach($hosts as $hostID => $host)
        {
            $options[] = array('text' => $host, 'value' => $hostID);
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
        $server         = $this->loadModel('pipeline')->getByID($serverID);
        $getProjectFunc = 'ajaxGet' . $server->type . 'Projects';

        $repos = $this->$getProjectFunc($serverID);
        return print(json_encode($this->repoZen->buildRepoPaths(array_column($repos, 'text', 'value'))));
    }

    /**
     * 获取Gitea项目。
     * Ajax get gitea projects.
     *
     * @param  int $giteaID
     * @access public
     * @return array
     */
    public function ajaxGetGiteaProjects(int $giteaID): array
    {
        $projects = $this->loadModel('gitea')->apiGetProjects($giteaID);

        $importedProjects = $this->repo->getImportedProjects($giteaID);

        $options = array();
        $options[] = array('text' => '', 'value' => '');;
        foreach($projects as $project)
        {
            if(in_array($project->full_name, $importedProjects)) continue;
            $options[] = array('text' => $project->full_name, 'value' => $project->full_name);
        }
        return $options;
    }

    /**
     * 获取Gogs项目。
     * Ajax get gogs projects.
     *
     * @param  int    $gogsID
     * @access public
     * @return array
     */
    public function ajaxGetGogsProjects(int $gogsID): array
    {
        $projects = $this->loadModel('gogs')->apiGetProjects($gogsID);

        $importedProjects = $this->repo->getImportedProjects($gogsID);

        $options = array();
        $options[] = array('text' => '', 'value' => '');;
        foreach($projects as $project)
        {
            if(in_array($project->full_name, $importedProjects)) continue;
            $options[] = array('text' => $project->full_name, 'value' => $project->full_name);
        }
        return $options;
    }

    /**
     * 获取Gitlab项目。
     * Ajax get gitlab projects.
     *
     * @param  int    $gitlabID
     * @param  string $projectIdList
     * @param  string $filter
     * @access public
     * @return array
     */
    public function ajaxGetGitlabProjects(int $gitlabID, string $projectIdList = '', string $filter = ''): array
    {
        $projects = $this->repo->getGitlabProjects($gitlabID, $filter);

        if(!$projects) return array();
        $projectIdList = $projectIdList ? explode(',', $projectIdList) : null;

        $options = array();
        $options[] = array('text' => '', 'value' => '');;
        foreach($projects as $project)
        {
            if(!empty($projectIdList) and $project and !in_array($project->id, $projectIdList)) continue;
            $options[] = array('text' => $project->name_with_namespace, 'value' => $project->id);
        }

        return $options;
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
        $server  = $this->loadModel('pipeline')->getByID($serverID);

        $result = new stdclass();
        $result->options = $options;
        $result->server  = $server;

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
        $savePath = $this->app->getDataRoot() . 'repo';
        if(!is_dir($savePath))
        {
            if(!is_writable($this->app->getDataRoot())) return $this->sendError(sprintf($this->lang->repo->error->noWritable, dirname($savePath)), true);
            mkdir($savePath, 0777, true);
        }

        $repo = $this->repo->getByID($repoID);
        $this->scm = $this->app->loadClass('scm');
        $this->scm->setEngine($repo);
        $url = $this->scm->getDownloadUrl($branch, $savePath);

        return $this->send(array('result' => 'success', 'callback' => "window.open('{$url}')"));
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

        $revision       = helper::safe64Decode(urldecode($this->post->revision));
        $sourceRevision = helper::safe64Decode(urldecode($this->post->sourceRevision));

        $this->scm->setEngine($repo);
        $blames = $this->scm->blame($entry, $revision);
        if(!$blames) $blames =$this->scm->blame($entry, $sourceRevision);

        return $this->send(array('result' => 'success', 'blames' => $blames));
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

        if($repo->SCM == 'Gitlab') return print(json_encode($this->repo->getGitlabFilesByPath($repo, $path, $branch)));
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
        if(!in_array($repo->SCM, $this->config->repo->notSyncSCM)) $this->locate(inLink('browse', "repoID=$repoID&objectID=$objectID"));

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

            $tag->createdBy = isset($tag->tagger->identity->name) ? zget($committers, $tag->tagger->identity->name) : '';

            $tag->createdDate = isset($tag->tagger->when) ? date('Y-m-d H:i:s', strtotime($tag->tagger->when)) : '';
            if(isset($tag->created_at)) $tag->createdDate = date('Y-m-d H:i:s', strtotime($tag->created_at));
            if($tag->createdDate) $showCreatedDate = true;

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
     * 浏览分支列表。
     * Browse branch list.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $label
     * @param  int    $showArchived
     * @param  string $keyword
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseBranch(int $repoID, int $objectID = 0, string $label = 'all', int $showArchived = 0, string $keyword = '', string $orderBy = 'date_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $repoID = $this->repoZen->processRepoID($repoID, $objectID);
        $this->commonAction($repoID, $objectID);

        $repo = $this->repo->getByID($repoID);
        if(!in_array($repo->SCM, $this->config->repo->notSyncSCM)) $this->locate(inLink('browse', "repoID=$repoID&objectID=$objectID"));

        $keyword = str_replace(' ', '+', urldecode($keyword));
        $keyword = htmlspecialchars(base64_decode($keyword));

        $this->scm->setEngine($repo);
        $branchList = $this->scm->branch($keyword ? $keyword : 'all', $orderBy, $recPerPage, $pageID, $label, $showArchived);
        if(count($branchList) == 0 && $pageID != 1) $this->locate(inLink('browseBranch', "repoID=$repoID&objectID=$objectID&label=$label&showArchived=$showArchived&keyword=$keyword&orderBy=$orderBy&recTotal=0&recPerPage=$recPerPage&pageID=1"));

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $pager->recPerPage = $recPerPage;
        $pager->recTotal = count($branchList) < $pager->recPerPage ? $pager->recPerPage * $pager->pageID : $pager->recPerPage * ($pager->pageID + 1);

        $committers = $this->loadModel('user')->getCommiters('account');
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

            $branch->ahead  = isset($branch->divergence->ahead) ? $branch->divergence->ahead : 0;
            $branch->behind = isset($branch->divergence->behind) ? $branch->divergence->behind : 0;
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
}
