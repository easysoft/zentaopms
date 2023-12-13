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
     * @param  int    $objectID  projectID|executionID
     * @access public
     * @return void
     */
    public function commonAction(int $repoID = 0, int $objectID = 0)
    {
        $tab = $this->app->tab;
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
            $features = $this->execution->getExecutionFeatures($execution);
            if(!$features['devops']) return print($this->locate($this->createLink('execution', 'task', "executionID=$objectID")));

            $this->loadModel('execution')->setMenu($objectID);
            $this->view->executionID = $objectID;
        }
        elseif($tab != 'admin')
        {
            $this->repo->setMenu($this->repos, $repoID);
        }

        if(empty($this->repos) && !in_array($this->methodName, array('create', 'setrules'))) return $this->locate($this->repo->createLink('create', "objectID=$objectID"));
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
        $repoList = empty($repoList) ? array() : $repoList[$pageID - 1];

        /* Get success jobs of sonarqube.*/
        $sonarRepoList = $this->loadModel('job')->getSonarqubeByRepo(helper::arrayColumn($repoList, 'id'));
        $successJobs   = $this->loadModel('compile')->getSuccessJobs(helper::arrayColumn($sonarRepoList, 'id'));

        $products = $this->loadModel('product')->getPairs('all', 0, '', 'all');
        $projects = $this->loadModel('project')->getPairs();

        $this->repoZen->buildRepoSearchForm($products, $projects, $objectID, $orderBy, $recPerPage, $pageID, $param);

        $this->view->title         = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->browse;
        $this->view->type          = $type;
        $this->view->orderBy       = $orderBy;
        $this->view->objectID      = $objectID;
        $this->view->pager         = $pager;
        $this->view->repoList      = $repoList;
        $this->view->products      = $products;
        $this->view->projects      = $projects;
        $this->view->sonarRepoList = $sonarRepoList;
        $this->view->successJobs   = $successJobs;

        $this->display();
    }

    /**
     * 创建版本库。
     * Create a repo.
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

            if($this->post->SCM == 'Gitlab')
            {
                /* Add webhook. */
                $repo = $this->repo->getByID($repoID);
                $this->loadModel('gitlab')->updateCodePath($repo->serviceHost, $repo->serviceProject, $repo->id);
                $this->repo->updateCommitDate($repoID);
            }

            $this->loadModel('action')->create('repo', $repoID, 'created');

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $repoID));
            $link = $this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID", '', false) . '#app=' . $this->app->tab;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
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

            if($formData->SCM == 'Gitlab')
            {
                /* Add webhook. */
                $repo = $this->repo->getByID($repoID);
                $this->loadModel('gitlab')->updateCodePath($repo->serviceHost, $repo->serviceProject, $repo->id);
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
     * @param  int    $taskID
     * @param  int    $executionID
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function createBranch(int $taskID, int $executionID, int $repoID = 0)
    {
        $repoList = $this->repo->getList($executionID);
        if(!$repoList) return $this->send(array('result' => 'fail', 'message' => $this->lang->repo->error->noFound));

        if(!$repoID) $repoID = $this->post->repoID;
        if(!$repoID || !isset($repoList[$repoID])) $repoID = key($repoList);
        $this->scm->setEngine($repoList[$repoID]);

        if(!empty($_POST))
        {
            $branch = form::data($this->config->repo->form->createBranch)->get();
            $result = $this->scm->createBranch($branch->name, $branch->from);
            if($result['result'] == 'fail') return $this->sendError($this->lang->repo->error->createdFail . ': ' . $result['message']);

            $this->repo->saveTaskRelation((int)$repoID, $taskID, $branch->name);
            $this->loadModel('action')->create('task', $taskID, 'createRepoBranch', '', $branch->name);
            $this->sendSuccess(array('closeModal' => true, 'load' => true));
        }

        $repoPairs = array();
        foreach($repoList as $repo) $repoPairs[$repo->id] = $repo->name;

        $this->view->repoPairs   = $repoPairs;
        $this->view->repoID      = $repoID;
        $this->view->taskID      = $taskID;
        $this->view->branches    = $this->scm->branch();
        $this->view->executionID = $executionID;
        $this->display();
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
        if($error) return $this->send(array('result' => 'fail', 'message' => $error));

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
     * @param  string $showBug
     * @param  string $encoding
     * @access public
     * @return void
     */
    public function monaco(int $repoID, int $objectID = 0, string $entry = '', string $revision = 'HEAD', string $showBug = 'false', string $encoding = '')
    {
        $this->commonAction($repoID, $objectID);

        $file     = $entry;
        $entry    = $this->repo->decodePath($entry);
        $entry    = urldecode($entry);
        $pathInfo = helper::mbPathinfo($entry);

        $repo = $this->repo->getByID($repoID);
        if($repo->SCM == 'Gitlab') $repo = $this->repo->processGitService($repo, true);

        $dropMenus = array();
        if(in_array($repo->SCM, $this->config->repo->gitTypeList)) $dropMenus = $this->repoZen->getBranchAndTagItems($repo, $this->cookie->repoBranch);

        if($this->app->tab == 'execution') $this->view->executionID = $objectID;
        $this->view->title     = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->view;
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
     * @param  string $showBug
     * @param  string $encoding
     * @access public
     * @return void
     */
    public function view(int $repoID, int $objectID = 0, string $entry = '', string $revision = 'HEAD', string $showBug = 'false', string $encoding = '')
    {
        set_time_limit(0);
        if($this->get->repoPath) $entry = $this->get->repoPath;
        if($repoID == 0) $repoID = $this->session->repoID;
        if($revision != 'HEAD')
        {
            setCookie("repoBranch", $revision, $this->config->cookieLife, $this->config->webRoot, '', false, true);
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

            $this->locate($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($path) . "&oldrevision=$oldRevision&newRevision=$newRevision"));
        }

        /* Set menu and session. */
        $this->commonAction($repoID, $objectID);
        $this->repoZen->setBrowseSession();

        /* Get repo and synchronous commit. */
        $repo = $this->repo->getByID($repoID);
        if($repo->SCM == 'Git' && !is_dir($repo->path)) return $this->sendError(sprintf($this->lang->repo->error->notFound, $repo->name, $repo->path), $this->repo->createLink('maintain'));
        if(!$repo->synced) return $this->locate($this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID"));

        /* Set branch or tag for git. */
        $branchID = $branchID ? base64_decode(helper::safe64Decode($branchID)) : '';
        list($branchID, $branches, $tags) = $this->repoZen->setBranchTag($repo, $branchID);

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

        $this->view->title       = $this->lang->repo->common;
        $this->view->repo        = $repo;
        $this->view->revisions   = $revisions;
        $this->view->revision    = $revision;
        $this->view->infos       = $infos;
        $this->view->repoID      = $repoID;
        $this->view->branches    = $branches;
        $this->view->tags        = $tags;
        $this->view->branchID    = $branchID;
        $this->view->objectID    = $objectID;
        $this->view->pager       = $pager;
        $this->view->path        = urldecode($path);
        $this->view->logType     = $type;
        $this->view->cloneUrl    = $this->repo->getCloneUrl($repo);
        $this->view->repoPairs   = $this->repo->getRepoPairs($this->app->tab, $objectID);
        $this->view->branchOrTag = $branchOrTag;

        $this->display();
    }

    /**
     * 代码提交记录列表。
     * show repo log.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $revision
     * @param  string $type
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function log(int $repoID = 0, int $objectID = 0, string $entry = '', string $revision = 'HEAD', string $type = 'dir', int $recTotal = 0, int $recPerPage = 50, int $pageID = 1)
    {
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

        $this->commonAction($repoID, $objectID);
        $this->scm->setEngine($repo);
        $info = $this->scm->info($entry, $revision);

        $logs = $this->repo->getCommits($repo, $entry, $revision, $type, $pager);

        $this->view->repo       = $repo;
        $this->view->title      = $this->lang->repo->common;
        $this->view->logs       = $logs;
        $this->view->revision   = $revision;
        $this->view->repoID     = $repoID;
        $this->view->objectID   = $objectID;
        $this->view->entry      = $entry;
        $this->view->type       = $type;
        $this->view->branchID   = $this->cookie->repoBranch;
        $this->view->entry      = urldecode($entry);
        $this->view->path       = urldecode($entry);
        $this->view->file       = urldecode($file);
        $this->view->pager      = $pager;
        $this->view->info       = $info;
        $this->display();
    }

    /**
     * 单个代码提交记录。
     * Show repo revision.
     *
     * @param int    $repoID
     * @param int    $objectID
     * @param int    $revision
     * @access public
     * @return void
     */
    public function revision(int $repoID, int $objectID = 0, string $revision = '')
    {
        if($repoID == 0) $repoID = $this->session->repoID;
        $repo = $this->repo->getByID($repoID);

        $this->scm->setEngine($repo);
        $log = $this->scm->log('', $revision, $revision);

        $revision = $this->repo->getHistoryRevision($repoID, $log[0]->revision);
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
            if($revision and in_array($repo->SCM, $this->config->repo->gitTypeList)) $oldRevision = "{$revision}^";
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
        $this->view->branchID     = $this->cookie->repoBranch;
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
     * @param  string $showBug
     * @param  string $encoding
     * @param  bool   $isBranchOrTag
     * @access public
     * @return void
     */
    public function diff(int $repoID, int $objectID = 0, string $entry = '', string $oldRevision = '', string $newRevision = '', string $showBug = 'false', string $encoding = '', bool $isBranchOrTag = false)
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

        if($repo->SCM == 'Git' && !is_dir($repo->path)) return $this->sendError(sprintf($this->lang->repo->error->notFound, $repo->name, $repo->path), $this->repo->createLink('maintain'));

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
        $this->view->repoID        = $repoID;
        $this->view->branchID      = (string) $this->cookie->repoBranch;
        $this->view->objectID      = $objectID;
        $this->view->repo          = $repo;
        $this->view->diffs         = $diffs;
        $this->view->newRevision   = $newRevision;
        $this->view->oldRevision   = $oldRevision;
        $this->view->isBranchOrTag = $isBranchOrTag;
        $this->view->title         = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->diff;

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
     * @param  bool   $isBranchOrTag
     * @access public
     * @return void
     */
    public function download(int $repoID, string $path, string $fromRevision = 'HEAD', string $toRevision = '', string $type = 'file', bool $isBranchOrTag = false)
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

        $extension = ltrim(strrchr($fileName, '.'), '.');
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

        $repoID = (int)$this->session->repoID;
        $this->commonAction($repoID);

        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('story');
        if(is_string($this->config->repo->rules)) $this->config->repo->rules = json_decode($this->config->repo->rules, true);

        $this->view->title = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->setRules;

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
        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->showSyncCommit;
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

        $repo    = $this->repo->getByID($repoID);
        $product = $this->loadModel('product')->getById((int)$repo->product);
        $modules = $this->loadModel('tree')->getOptionMenu((int)$repo->product, 'story');
        $queryID = $browseType == 'bySearch' ? (int)$param : 0;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->repoZen->buildStorySearchForm($repoID, $revision, $browseType, $queryID, $product, $modules);

        $this->view->modules        = $modules;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->allStories     = $this->repoZen->getLinkStories($repoID, $revision, $browseType, $product, $orderBy, $pager, $queryID);
        $this->view->product        = $product;
        $this->view->repoID         = $repoID;
        $this->view->revision       = $revision;
        $this->view->browseType     = $browseType;
        $this->view->param          = $param;
        $this->view->orderBy        = $orderBy;
        $this->view->pager          = $pager;
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

        $repo    = $this->repo->getByID($repoID);
        $product = $this->loadModel('product')->getById((int)$repo->product);
        $modules = $this->loadModel('tree')->getOptionMenu((int)$product->id, 'bug');
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->repoZen->buildBugSearchForm($repoID, $revision, $browseType, $queryID, $product, $modules);

        $this->view->modules     = $modules;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->allBugs     = $this->repoZen->getLinkBugs($repoID, $revision, $browseType, $product, $orderBy, $pager, $queryID);
        $this->view->product     = $product;
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

        $repo    = $this->repo->getByID($repoID);
        $product = $this->loadModel('product')->getById((int)$repo->product);
        $modules = $this->loadModel('tree')->getOptionMenu((int)$product->id, 'task');
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get executions by product. */
        $productExecutions   = $this->product->getExecutionPairsByProduct($product->id);
        $productExecutionIDs = array_filter(array_keys($productExecutions));

        /* Build search form. */
        $this->repoZen->buildTaskSearchForm($repoID, $revision, $browseType, $queryID, $product, $modules, $productExecutions);

        $this->view->modules      = $modules;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->allTasks     = $this->repoZen->getLinkTasks($repoID, $revision, $browseType, $product, $orderBy, $pager, $queryID, $productExecutionIDs);
        $this->view->product      = $product;
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
     * @param  int    $server
     * @access public
     * @return void
     */
    public function import(int $server = 0)
    {
        if($this->viewType !== 'json') $this->commonAction();

        if($_POST)
        {
            $repos = $this->repoZen->prepareBatchCreate();

            if($repos) $this->repo->batchCreate($repos, (int)$_POST['serviceHost']);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->repo->createLink('maintain')));
        }

        $gitlabList = $this->loadModel('gitlab')->getList();
        $gitlab     = empty($server) ? array_shift($gitlabList) : $this->gitlab->getById($server);

        $repoList = $gitlab ? $this->repoZen->getGitlabNotExistRepos($gitlab) : array();
        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');

        $this->view->title       = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->importAction;
        $this->view->gitlabPairs = $this->gitlab->getPairs();
        $this->view->products    = $products;
        $this->view->projects    = $this->product->getProjectPairsByProductIDList(array_keys($products));
        $this->view->gitlab      = $gitlab;
        $this->view->repoList    = array_values($repoList);
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
     * @param  string $showBug     // Used for biz.
     * @param  string $encoding
     * @access public
     * @return void
     */
    public function ajaxGetDiffEditorContent(int $repoID, int $objectID = 0, string $entry = '', string $oldRevision = '', string $newRevision = '', string $showBug = 'false', string $encoding = '')
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

        $this->scm->setEngine($repo);
        $info = $this->scm->info($entry, $nRevision);

        $this->view->title       = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->diff;
        $this->view->type        = 'diff';
        $this->view->encoding    = str_replace('-', '_', $encoding);
        $this->view->repoID      = $repoID;
        $this->view->objectID    = $objectID;
        $this->view->repo        = $repo;
        $this->view->revision    = $nRevision;
        $this->view->oldRevision = $revision;
        $this->view->file        = $file;
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
     * @param  string $showBug
     * @param  string $encoding
     * @access public
     * @return void
     */
    public function ajaxGetEditorContent(int $repoID, int $objectID = 0, string $entry = '', string $revision = 'HEAD', string $showBug = 'false', string $encoding = '')
    {
        if(!$entry) $entry = (string) $this->cookie->repoCodePath;

        $file     = $entry;
        $repo     = $this->repo->getByID($repoID);
        $entry    = urldecode($this->repo->decodePath($entry));
        $revision = str_replace('*', '-', $revision);

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

        $this->view->title       = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->view;
        $this->view->type        = 'view';
        $this->view->showBug     = $showBug;
        $this->view->repoID      = $repoID;
        $this->view->revision    = $revision;
        $this->view->oldRevision = '';
        $this->view->file        = $file;
        $this->view->entry       = $entry;
        $this->view->suffix      = $suffix;
        $this->view->content     = $content ? $content : '';
        $this->view->pathInfo    = $pathInfo;
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
            if($syncLog) return print($syncLog);
        }

        $this->commonAction($repoID);
        $this->scm->setEngine($repo);

        $branchID = (string)$this->cookie->syncBranch;
        $branches = $this->repoZen->getSyncBranches($repo, $branchID);

        $logs    = array();
        $version = 1;
        if($repo->SCM != 'Gitlab')
        {
            $latestInDB = $this->repo->getLatestCommit($repoID, false);

            $version  = empty($latestInDB) ? 1 : $latestInDB->commit + 1;
            $revision = $version == 1 ? 'HEAD' : (in_array($repo->SCM, array('Git', 'Gitea', 'Gogs')) ? $latestInDB->commit : $latestInDB->revision);
            $logs     = $this->scm->getCommits($revision, $type == 'batch' ? $this->config->repo->batchNum : 0, $branchID);
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
     * @return object
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
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $repoID, string $module = 'repo', string $method = 'browse', int $projectID = 0)
    {
        if($module == 'repo' and !in_array($method, array('review', 'diff'))) $method = 'browse';
        if($module == 'mr' && $method != 'create')  $method = 'browse';
        if($module == 'job') $method = 'browse';
        if($module == 'compile' and $method == 'logs') $method = 'browse';
        if($module == 'bug' and $method == 'view')
        {
            $module = 'repo';
            $method = 'review';
        }

        $params = '';
        if($projectID && $method == 'browse') $params = "&branchID=&objectID=$projectID";

        /* Get repo group by type. */
        $repoType  = $module == 'mr' ? 'git' : '';
        $repoGroup = $this->repo->getRepoGroup('project', $projectID, $repoType);

        $this->view->repoID    = $repoID;
        $this->view->repoGroup = $repoGroup;
        $this->view->link      = $this->createLink($module, $method, "repoID=%s" . $params) . ($projectID ? '#app=project' : '');

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
     * @param  int    $scm
     * @access public
     * @return void
     */
    public function ajaxGetHosts(string $scm)
    {
        $hosts = $this->loadModel(strtolower($scm))->getPairs();

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

        $this->$getProjectFunc($serverID);
    }

    /**
     * 获取Gitea项目。
     * Ajax get gitea projects.
     *
     * @param  string $gitlabID
     * @param  string $projectIdList
     * @access public
     * @return void
     */
    public function ajaxGetGiteaProjects(int $giteaID)
    {
        $projects = $this->loadModel('gitea')->apiGetProjects($giteaID);

        $options = array();
        $options[] = array('text' => '', 'value' => '');;
        foreach($projects as $project)
        {
            $options[] = array('text' => $project->full_name, 'value' => $project->full_name);
        }
        return print(json_encode($options));
    }

    /**
     * 获取Gogs项目。
     * Ajax get gogs projects.
     *
     * @param  string $gitlabID
     * @param  string $projectIdList
     * @access public
     * @return void
     */
    public function ajaxGetGogsProjects(int $gogsID)
    {
        $projects = $this->loadModel('gogs')->apiGetProjects($gogsID);

        $options = array();
        $options[] = array('text' => '', 'value' => '');;
        foreach($projects as $project)
        {
            $options[] = array('text' => $project->full_name, 'value' => $project->full_name);
        }
        return print(json_encode($options));
    }

    /**
     * 获取Gitlab项目。
     * Ajax get gitlab projects.
     *
     * @param  string $gitlabID
     * @param  string $token
     * @access public
     * @return void
     */
    public function ajaxGetGitlabProjects(int $gitlabID, string $projectIdList = '', string $filter = '')
    {
        $projects = $this->repo->getGitlabProjects($gitlabID, $filter);

        if(!$projects) return print('[]');
        $projectIdList = $projectIdList ? explode(',', $projectIdList) : null;

        $options = array();
        $options[] = array('text' => '', 'value' => '');;
        foreach($projects as $project)
        {
            if(!empty($projectIdList) and $project and !in_array($project->id, $projectIdList)) continue;
            $options[] = array('text' => $project->name_with_namespace, 'value' => $project->id);
        }
        return print(json_encode($options));
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
     * @param  string $type  gitlab
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

        $this->locate($url);
    }

    /**
     * 根据代码库和提交获取关联信息的标题列表。
     * Get relation by commit.
     *
     * @param  int    $repoID
     * @param  string $commit
     * @access public
     * @return object
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

        $this->scm->setEngine($repo);
        $blames = $this->scm->blame($entry, $this->post->revision);
        if(!$blames) $blames =$this->scm->blame($entry, $this->post->sourceRevision);

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
     * @return string
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
     * @return object
     */
    public function ajaxGetFileCommitInfo()
    {
        $repo   = $this->repo->getByID((int)$this->post->repoID);
        $commit = $this->loadModel('gitlab')->getFileLastCommit($repo, $this->post->path, $this->post->branch);
        $commit->comment = $this->repo->replaceCommentLink($commit->message);
        echo json_encode($commit);
    }
}
