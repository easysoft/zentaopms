<?php
/**
 * The control file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Yidong Wang, Jinyong Zhu
 * @package     repo
 * @version     $Id$
 * @link        http://www.zentao.net
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
            echo js::alert($this->lang->repo->error->useless);
            return print(js::locate('back'));
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
            if($project->model === 'kanban') return print($this->locate($this->createLink('project', 'index', "projectID=$objectID")));

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

        if(empty($this->repos) and !in_array($this->methodName, array('create', 'setrules'))) return print($this->locate($this->repo->createLink('create', "objectID=$objectID")));
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
            $formData         = form::data($this->config->repo->form->createRepo);
            $isPipelineServer = in_array(strtolower($this->post->SCM), $this->config->repo->gitServiceList) ? true : false;
            $repo             = $this->repoZen->prepareCreateRepo($formData, $isPipelineServer);

            /* Create a repo. */
            if($repo) $repoID = $this->repo->createRepo($repo, $_POST['namespace']);
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
            if($result['result'] == 'fail') return $this->send(array('result' => 'fail', 'message' => $this->lang->repo->error->createdFail . ': ' . $result['message']));

            $this->repo->saveTaskRelation($repoID, $taskID, $branch->name);
            $this->loadModel('action')->create('task', $taskID, 'createRepoBranch', '', $branch->name);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => 'parent.location.reload()'));
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

            if($editData) $noNeedSync = $this->repo->update($editData, $repoID, $isPipelineServer);
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
     * Delete repo.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete(int $repoID, int $objectID = 0, string $confirm = 'no')
    {
        if($confirm == 'no') return print(js::confirm($this->lang->repo->notice->delete, $this->repo->createLink('delete', "repoID=$repoID&objectID=$objectID&confirm=yes")));

        $error = $this->repoZen->checkDeleteError($repoID);
        if($error) return $this->send(array('result' => 'fail', 'message' => $error));

        $this->repo->deleteRepo($repoID);
        if(dao::isError()) return print(js::error(dao::getError()));

        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
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
    public function monaco($repoID, $objectID = 0, $entry = '', $revision = 'HEAD', $showBug = 'false', $encoding = '')
    {
        $this->commonAction($repoID, $objectID);

        $file     = $entry;
        $repo     = $this->repo->getByID($repoID);
        $entry    = $this->repo->decodePath($entry);
        $entry    = urldecode($entry);
        $pathInfo = helper::mbPathinfo($entry);

        if($repo->SCM == 'Gitlab') $repo = $this->repo->processGitService($repo, true);

        if($this->app->tab == 'execution') $this->view->executionID = $objectID;
        $this->view->title       = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->view;
        $this->view->dropMenus   = $this->repoZen->getBranchAndTagItems($repo, $this->cookie->repoBranch);
        $this->view->type        = 'view';
        $this->view->branchID    = $this->cookie->repoBranch;
        $this->view->showBug     = $showBug;
        $this->view->encoding    = $encoding;
        $this->view->repoID      = $repoID;
        $this->view->objectID    = $objectID;
        $this->view->repo        = $repo;
        $this->view->revision    = $revision;
        $this->view->file        = $file;
        $this->view->entry       = $entry;
        $this->view->pathInfo    = $pathInfo;
        $this->view->tree        = $this->repoZen->getViewTree($repo, '', $revision);

        $this->display();
    }

    /**
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
    public function view($repoID, $objectID = 0, $entry = '', $revision = 'HEAD', $showBug = 'false', $encoding = '')
    {
        set_time_limit(0);
        if($this->get->repoPath) $entry = $this->get->repoPath;
        $this->repo->setBackSession('view', true);
        if($repoID == 0) $repoID = $this->session->repoID;
        if($revision != 'HEAD')
        {
            setCookie("repoBranch", $revision, $this->config->cookieLife, $this->config->webRoot, '', false, true);
            $this->cookie->set('repoBranch', $revision);
        }

        $this->commonAction($repoID, $objectID);
        $repo = $this->repo->getByID($repoID);
        session_start();
        $this->session->set('storyList', inlink('view',  "repoID=$repoID&objectID=$objectID&entry=$entry&revision=$revision&showBug=$showBug&encoding=$encoding"), 'product');
        session_write_close();

        $browser = helper::getBrowser();
        if($browser['name'] != 'ie') return print($this->fetch('repo', 'monaco', "repoID=$repoID&objectID=$objectID&entry=$entry&revision=$revision&showBug=$showBug&encoding=$encoding"));

        if($_POST)
        {
            $oldRevision = isset($this->post->revision[1]) ? $this->post->revision[1] : '';
            $newRevision = isset($this->post->revision[0]) ? $this->post->revision[0] : '';

            $this->locate($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=$entry&oldrevision=$oldRevision&newRevision=$newRevision"));
        }

        $file     = $entry;
        $entry    = $this->repo->decodePath($entry);
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
        if(!$suffix or (!array_key_exists($suffix, $this->config->program->suffix) and strpos($this->config->repo->images, "|$suffix|") === false)) $suffix = $this->repo->isBinary($content, $suffix) ? 'binary' : 'c';

        if(strpos($this->config->repo->images, "|$suffix|") !== false)
        {
            $content = base64_encode($content);
        }
        elseif($encoding != 'utf-8')
        {
            $content = helper::convertEncoding($content, $encoding);
        }

        $this->app->loadClass('pager', true);
        $pager = new pager(0, 10, 1);

        $logType   = 'file';
        $revisions = $this->repo->getCommits($repo, '/' . $entry, 'HEAD', $logType, $pager);

        $i = 0;
        foreach($revisions as $log)
        {
            if($revision == 'HEAD' and $i == 0) $revision = $log->revision;
            if($revision == $log->revision) $revisionName = in_array($repo->SCM, $this->config->repo->gitTypeList) ?  $this->repo->getGitRevisionName($log->revision, $log->commit) : $log->revision;
            $i++;
        }
        if(!isset($revisionName))
        {
            if(in_array($repo->SCM, $this->config->repo->gitTypeList)) $gitCommit = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('revision')->eq($revision)->andWhere('repo')->eq($repo->id)->fetch('commit');
            $revisionName = (in_array($repo->SCM, $this->config->repo->gitTypeList) and isset($gitCommit)) ? $this->repo->getGitRevisionName($revision, $gitCommit) : $revision;
        }

        $this->view->revisions    = $revisions;
        $this->view->title        = $this->lang->repo->common;
        $this->view->type         = 'view';
        $this->view->showBug      = $showBug;
        $this->view->encoding     = str_replace('-', '_', $encoding);
        $this->view->repoID       = $repoID;
        $this->view->branchID     = $this->cookie->repoBranch;
        $this->view->objectID     = $objectID;
        $this->view->repo         = $repo;
        $this->view->revision     = $revision;
        $this->view->revisionName = $revisionName;
        $this->view->preAndNext   = $this->repo->getPreAndNext($repo, '/' . $entry, $revision);
        $this->view->file         = $file;
        $this->view->entry        = $entry;
        $this->view->path         = $entry;
        $this->view->suffix       = $suffix;
        $this->view->content      = $content;
        $this->view->pager        = $pager;
        $this->view->logType      = $logType;
        $this->view->info         = $info;
        $this->view->pathInfo     = $pathInfo;

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->view;
        $this->display();
    }

    /**
     * Browse repo.
     *
     * @param  int    $repoID
     * @param  string $branchID
     * @param  int    $objectID
     * @param  string $path
     * @param  string $revision
     * @param  int    $refresh
     * @param  string $branchOrTag branch|tag
     * @access public
     * @return void
     */
    public function browse($repoID = 0, $branchID = '', $objectID = 0, $path = '', $revision = 'HEAD', $refresh = 0, $branchOrTag = 'branch',  $type = 'dir', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $repoID                 = $this->repo->saveState($repoID, $objectID);
        $originBranchID         = $branchID;
        if($branchID) $branchID = base64_decode(helper::safe64Decode($branchID));

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
        if($repo->SCM == 'Git' and !is_dir($repo->path))
        {
            $error = sprintf($this->lang->repo->error->notFound, $repo->name, $repo->path);
            return print(js::error($error) . js::locate($this->repo->createLink('maintain')));
        }
        if(!$repo->synced) $this->locate($this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID"));

        /* Set branch or tag for git. */
        $branchInfo = $tagInfo = false;
        if($repo->SCM == 'Gitlab') list($branchInfo, $tagInfo) = $this->repoZen->getBrowseInfo($repo);
        list($branchID, $branches, $tags, $branchesAndTags) = $this->repoZen->setBranchTag($repo, $branchID, $branchInfo, $tagInfo);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Refresh repo. */
        if(empty($refresh) and $this->cookie->repoRefresh) $refresh = $this->cookie->repoRefresh;
        if($refresh)
        {
            $this->repo->updateCommit($repoID, $objectID, $originBranchID);
            if($repo->SCM == 'Gitlab') $this->repo->checkDeletedBranches($repoID, $branches);
        }
        if($this->cookie->repoRefresh) helper::setcookie('repoRefresh', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);

        /* Get revisions. */
        $revisions    = $this->repoZen->getCommits($repo, $path, $revision, $type, $pager, $objectID);
        $lastRevision = current($revisions);
        $lastRevision = empty($lastRevision) ? new stdclass() : $lastRevision;

        if($path == '') $this->repoZen->updateLastCommit($repo, $lastRevision);

        /* Get files info. */
        $base64BranchID = helper::safe64Encode(base64_encode($branchID));
        $infos          = $this->repoZen->getFilesInfo($repo, $path, $branchID, $refresh, $revision, $lastRevision, $base64BranchID, $objectID);

        /* Synchronous commit only in root path. */
        if(in_array($repo->SCM, $this->config->repo->gitTypeList) && $repo->SCM != 'Gitlab' && empty($path) && $infos && empty($revisions)) $this->locate($this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID&branch=" . helper::safe64Encode(base64_encode($this->cookie->repoBranch))));

        $this->view->title           = $this->lang->repo->common;
        $this->view->repo            = $repo;
        $this->view->revisions       = $revisions;
        $this->view->revision        = $revision;
        $this->view->infos           = $infos;
        $this->view->repoID          = $repoID;
        $this->view->branches        = $branches;
        $this->view->tags            = $tags;
        $this->view->branchesAndTags = $branchesAndTags;
        $this->view->branchID        = $branchID;
        $this->view->objectID        = $objectID;
        $this->view->currentProject  = $this->app->tab == 'project' ? $this->loadModel('project')->getByID($objectID) : null;
        $this->view->pager           = $pager;
        $this->view->path            = urldecode($path);
        $this->view->logType         = $type;
        $this->view->cloneUrl        = $this->repo->getCloneUrl($repo);
        $this->view->repoPairs       = $this->repo->getRepoPairs($this->app->tab,$objectID);
        $this->view->cacheTime       = isset($lastRevision->time) ? date('m-d H:i', strtotime($lastRevision->time)) : date('m-d H:i');
        $this->view->branchOrTag     = $branchOrTag;
        $this->view->syncedRF        = strpos(",{$this->config->repo->synced},", ",$repoID,") !== false; //Check has synced rename files.

        $this->display();
    }

    /**
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
    public function log($repoID = 0, $objectID = 0, $entry = '', $revision = 'HEAD', $type = 'dir', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        if($this->get->repoPath) $entry = $this->get->repoPath;
        $this->repo->setBackSession('log', true);
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

            $this->locate($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($path) . "&oldrevision=$oldRevision&newRevision=$newRevision"));
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
     * Show repo revision.
     *
     * @param int    $repoID
     * @param int    $objectID
     * @param int    $revision
     * @param string $root
     * @param string $type
     *
     * @access public
     * @return void
     */
    public function revision($repoID, $objectID = 0, $revision = '')
    {
        if($repoID == 0) $repoID = $this->session->repoID;
        $repo = $this->repo->getByID($repoID);

        $this->scm->setEngine($repo);
        $log = $this->scm->log('', $revision, $revision);

        $history = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('revision')->eq($log[0]->revision)->andWhere('repo')->eq($repoID)->fetch();
        if($history)
        {
            if(in_array($repo->SCM, $this->config->repo->gitTypeList))
            {
                $thisAndPrevRevisions = $this->scm->exec("rev-list -n 2 {$history->revision} --");

                array_shift($thisAndPrevRevisions);
                if($thisAndPrevRevisions) $oldRevision = array_shift($thisAndPrevRevisions);
            }
            else
            {
                $oldRevision = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('revision')->lt($history->revision)->andWhere('repo')->eq($repoID)->orderBy('revision_desc')->limit(1)->fetch('revision');
            }
        }

        if(empty($oldRevision))
        {
            $oldRevision = '^';
            if($history and in_array($repo->SCM, $this->config->repo->gitTypeList)) $oldRevision = "{$history->revision}^";
        }

        $this->locate($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=&oldrevision=$oldRevision&newRevision={$log[0]->revision}"));
    }

    /**
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
    public function blame($repoID, $objectID = 0, $entry = '', $revision = 'HEAD', $encoding = '')
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

        $log = in_array($repo->SCM, $this->config->repo->gitTypeList) ? $this->dao->select('revision,commit')->from(TABLE_REPOHISTORY)->where('revision')->eq($revision)->andWhere('repo')->eq($repo->id)->fetch() : '';

        $this->view->title        = $this->lang->repo->common;
        $this->view->repoID       = $repoID;
        $this->view->branchID     = $this->cookie->repoBranch;
        $this->view->objectID     = $objectID;
        $this->view->repo         = $repo;
        $this->view->revision     = $revision;
        $this->view->entry        = $entry;
        $this->view->file         = $file;
        $this->view->encoding     = str_replace('-', '_', $encoding);
        $this->view->historys     = in_array($repo->SCM, $this->config->repo->gitTypeList) ? $this->dao->select('revision,commit')->from(TABLE_REPOHISTORY)->where('revision')->in($revisions)->andWhere('repo')->eq($repo->id)->fetchPairs() : '';
        $this->view->revisionName = ($log and in_array($repo->SCM, $this->config->repo->gitTypeList)) ? $this->repo->getGitRevisionName($log->revision, $log->commit) : $revision;
        $this->view->blames       = $blames;
        $this->display();
    }

    /**
     * Show diff.
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
    public function diff($repoID, $objectID = 0, $entry = '', $oldRevision = '', $newRevision = '', $showBug = 'false', $encoding = '', $isBranchOrTag = false)
    {
        if($isBranchOrTag)
        {
            $oldRevision = strtr($oldRevision, '*', '-');
            $newRevision = strtr($newRevision, '*', '-');
            if($isBranchOrTag)
            {
                $oldRevision = urldecode(helper::safe64Decode($oldRevision));
                $newRevision = urldecode(helper::safe64Decode($newRevision));
            }
        }
        else
        {
            $oldRevision = urldecode(urldecode($oldRevision)); //Fix error.
        }

        $this->commonAction($repoID, $objectID);

        if($this->get->repoPath) $entry = $this->get->repoPath;
        $file  = $entry;
        $repo  = $this->repo->getByID($repoID);
        $entry = $this->repo->decodePath($entry);

        if($repo->SCM == 'Git' and !is_dir($repo->path))
        {
            $error = sprintf($this->lang->repo->error->notFound, $repo->name, $repo->path);
            return print(js::error($error) . js::locate($this->repo->createLink('maintain')));
        }

        $pathInfo = pathinfo($entry);
        $suffix   = '';
        if(isset($pathInfo["extension"])) $suffix = strtolower($pathInfo["extension"]);

        $arrange = $this->cookie->arrange ? $this->cookie->arrange : 'inline';
        if($this->server->request_method == 'POST')
        {
            $oldRevision = isset($this->post->revision[1]) ? $this->post->revision[1] : '';
            $newRevision = isset($this->post->revision[0]) ? $this->post->revision[0] : '';

            if($this->post->arrange)
            {
                $arrange = $this->post->arrange;
                helper::setcookie('arrange', $arrange);
            }
            if($this->post->encoding)      $encoding      = $this->post->encoding;
            if($this->post->isBranchOrTag) $isBranchOrTag = $this->post->isBranchOrTag;

            $this->locate($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($entry) . "&oldrevision=$oldRevision&newRevision=$newRevision&showBug=&encoding=$encoding&isBranchOrTag=$isBranchOrTag"));
        }

        $info     = new stdClass();
        $diffs    = array();
        $encoding = empty($encoding) ? $repo->encoding : $encoding;
        $encoding = strtolower(str_replace('_', '-', $encoding));
        if($oldRevision !== '')
        {
            $this->scm->setEngine($repo);
            $info  = $this->scm->info($entry, $newRevision);
            $diffs = $this->scm->diff($entry, $oldRevision, $newRevision, 'yes', $isBranchOrTag ? 'isBranchOrTag': '');
        }
        foreach($diffs as $diff)
        {
            if($encoding != 'utf-8')
            {
                $diff->fileName = helper::convertEncoding($diff->fileName, $encoding);
                if(empty($diff->contents)) continue;
                foreach($diff->contents as $content)
                {
                    if(empty($content->lines)) continue;
                    foreach($content->lines as $lines)
                    {
                        if(empty($lines->line)) continue;
                        $lines->line = helper::convertEncoding($lines->line, $encoding);
                    }
                }
            }
        }

        /* When arrange is appose then adjust data for show them easy.*/
        if($arrange == 'appose')
        {

            foreach($diffs as $diffFile)
            {
                if(empty($diffFile->contents)) continue;
                foreach($diffFile->contents as $content)
                {
                    $old = array();
                    $new = array();
                    foreach($content->lines as $line)
                    {
                        if($line->type != 'new') $old[$line->oldlc] = $line->line;
                        if($line->type != 'old') $new[$line->newlc] = $line->line;
                    }
                    $content->old = $old;
                    $content->new = $new;
                }
            }
        }

        $this->view->type        = 'diff';
        $this->view->showBug     = $showBug;
        $this->view->entry       = urldecode($entry);
        $this->view->suffix      = $suffix;
        $this->view->file        = $file;
        $this->view->repoID      = $repoID;
        $this->view->branchID    = (string) $this->cookie->repoBranch;
        $this->view->objectID    = $objectID;
        $this->view->repo        = $repo;
        $this->view->encoding    = str_replace('-', '_', $encoding);
        $this->view->arrange     = $arrange;
        $this->view->diffs       = $diffs;
        $this->view->newRevision = $newRevision;
        $this->view->oldRevision = $oldRevision;
        $this->view->revision    = $newRevision;
        $this->view->historys    = in_array($repo->SCM, $this->config->repo->gitTypeList) ? $this->dao->select('revision,commit')->from(TABLE_REPOHISTORY)->where('revision')->in("$oldRevision,$newRevision")->andWhere('repo')->eq($repo->id)->fetchPairs() : '';
        $this->view->info        = $info;

        $this->view->isBranchOrTag = $isBranchOrTag;
        $this->view->title         = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->diff;

        $this->display();
    }

    /**
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
    public function download($repoID, $path, $fromRevision = 'HEAD', $toRevision = '', $type = 'file', $isBranchOrTag = false)
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
        $this->fetch('file', 'sendDownHeader', array("fileName" => $fileName, "fileType" => $extension,  "content" => $content));
    }

    /**
     * Set Rules.
     *
     * @access public
     * @return void
     */
    public function setRules($module = '')
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.repo.rules', json_encode($this->post->rules));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $repoID = (int)$this->session->repoID;
        $this->commonAction($repoID);
        $this->lang->switcherMenu = '';

        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('story');
        if(is_string($this->config->repo->rules)) $this->config->repo->rules = json_decode($this->config->repo->rules, true);

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->setRules;

        $this->display();
    }

    /**
     * Show sync comment.
     *
     * @param  int    $repoID
     * @param  int    $objectID  projectID|executionID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function showSyncCommit($repoID = 0, $objectID = 0, $branch = '')
    {
        $this->commonAction($repoID, $objectID);

        if($repoID == 0) $repoID = $this->session->repoID;
        if($branch) $branch = base64_decode(helper::safe64Decode($branch));

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->showSyncCommit;

        $latestInDB = $this->repo->getLatestCommit($repoID);
        $this->view->version    = $latestInDB ? (int)$latestInDB->commit : 1;
        $this->view->repoID     = $repoID;
        $this->view->repo       = $this->repo->getByID($repoID);
        $this->view->objectID   = $objectID;
        $this->view->branch     = $branch;
        $this->view->browseLink = $this->repo->createLink('browse', "repoID=" . ($this->app->tab == 'devops' ? $repoID : '') . "&branchID=" . helper::safe64Encode(base64_encode($branch)) . "&objectID=$objectID", '', false) . "#app={$this->app->tab}";
        $this->display();
    }

    /**
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
     * Unlink object and commit revision.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $objectType story|task|bug
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function unlink($repoID, $revision, $objectType, $objectID)
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
     * Get diff editor content by ajax.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $oldRevision
     * @param  string $newRevision
     * @param  string $showBug
     * @param  string $encoding
     * @access public
     * @return void
     */
    public function ajaxGetDiffEditorContent($repoID, $objectID = 0, $entry = '', $oldRevision = '', $newRevision = '', $showBug = 'false', $encoding = '')
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
    public function ajaxGetEditorContent($repoID, $objectID = 0, $entry = '', $revision = 'HEAD', $showBug = 'false', $encoding = '')
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
        if(!$suffix or (!array_key_exists($suffix, $this->config->program->suffix) and strpos($this->config->repo->images, "|$suffix|") === false)) $suffix = $this->repo->isBinary($content, $suffix) ? 'binary' : 'c';

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
        $this->view->encoding    = str_replace('-', '_', $encoding);
        $this->view->repoID      = $repoID;
        $this->view->branchID    = $this->cookie->repoBranch;
        $this->view->objectID    = $objectID;
        $this->view->repo        = $repo;
        $this->view->revision    = $revision;
        $this->view->oldRevision = '';
        $this->view->file        = $file;
        $this->view->entry       = $entry;
        $this->view->path        = $entry;
        $this->view->info        = $info;
        $this->view->suffix      = $suffix;
        $this->view->content     = $content ? $content : '';
        $this->view->pathInfo    = $pathInfo;
        $this->view->showEditor  = (strpos($this->config->repo->images, "|$suffix|") === false and $suffix != 'binary') ? true : false;
        $this->display();
    }

    /**
     * Ajax sync comment.
     *
     * @param  int    $repoID
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxSyncCommit($repoID = 0, $type = 'batch')
    {
        set_time_limit(0);
        $repo = $this->repo->getByID($repoID);
        if(empty($repo)) return;
        if($repo->synced) return print($this->config->repo->repoSyncLog->finish);

        if(in_array($repo->SCM, array('Gitea', 'Gogs')))
        {
            $logFile = realPath($this->app->getTmpRoot() . $this->config->repo->repoSyncLog->logFilePrefix . strtolower($repo->SCM) . ".{$repo->name}.log");
            if($logFile)
            {
                $content  = file($logFile);
                foreach($content as $line)
                {
                    if($this->repo->strposAry($line, $this->config->repo->repoSyncLog->fatal) !== false) return print($line);
                    if($this->repo->strposAry($line, $this->config->repo->repoSyncLog->failed) !== false) return print($line);
                }

                $lastLine = $content[count($content) - 1];
                if($this->repo->strposAry($lastLine, $this->config->repo->repoSyncLog->done) === false)
                {
                    if($this->repo->strposAry($lastLine, $this->config->repo->repoSyncLog->emptyRepo) !== false)
                    {
                        @unlink($logFile);
                    }
                    elseif($this->repo->strposAry($lastLine, $this->config->repo->repoSyncLog->total) !== false)
                    {
                        $logContent = file_get_contents($logFile);
                        if($this->repo->strposAry($logContent, $this->config->repo->repoSyncLog->finishCount) !== false and $this->repo->strposAry($logContent, $this->config->repo->repoSyncLog->finishCompress) !== false)
                        {
                            @unlink($logFile);
                        }
                        else
                        {
                            return print($this->config->repo->repoSyncLog->one);
                        }
                    }
                    else
                    {
                        return print($this->config->repo->repoSyncLog->one);
                    }
                }
                else
                {
                    @unlink($logFile);
                }
            }
        }

        $this->commonAction($repoID);
        $this->scm->setEngine($repo);

        $branchID = '';
        if(in_array($repo->SCM, $this->config->repo->gitTypeList) and empty($branchID))
        {
            $branches = $this->scm->branch();
            if($branches)
            {
                /* Init branchID. */
                if($this->cookie->syncBranch) $branchID = $this->cookie->syncBranch;
                if(!isset($branches[$branchID])) $branchID = '';
                if(empty($branchID)) $branchID = 'master';

                /* Get unsynced branches. */
                unset($branches['master']);
                if($branchID != 'master')
                {
                    foreach($branches as $branch)
                    {
                        unset($branches[$branch]);
                        if($branch == $branchID) break;
                    }
                }

                $this->repo->setRepoBranch($branchID);
                helper::setcookie("syncBranch", $branchID, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
            }
        }

        $logs    = array();
        $version = 1;
        if($repo->SCM != 'Gitlab')
        {
            $latestInDB = $this->dao->select('t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
                ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
                ->where('t1.repo')->eq($repoID)
                ->beginIF($repo->SCM == 'Git' and $this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
                ->beginIF($repo->SCM == 'Gitlab' and $this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
                ->orderBy('t1.time')
                ->limit(1)
                ->fetch();

            $version  = empty($latestInDB) ? 1 : $latestInDB->commit + 1;
            $revision = $version == 1 ? 'HEAD' : (in_array($repo->SCM, array('Git', 'Gitea', 'Gogs')) ? $latestInDB->commit : $latestInDB->revision);
            if($type == 'batch')
            {
                $logs = $this->scm->getCommits($revision, $this->config->repo->batchNum, $branchID);
            }
            else
            {
                $logs = $this->scm->getCommits($revision, 0, $branchID);
            }
        }

        $commitCount = $this->repo->saveCommit($repoID, $logs, $version, $branchID);
        if(empty($commitCount))
        {
            if(!$repo->synced)
            {
                if(in_array($repo->SCM, $this->config->repo->gitTypeList))
                {
                    if($branchID) $this->repo->saveExistCommits4Branch($repo->id, $branchID);

                    $branchID = reset($branches);
                    setcookie("syncBranch", $branchID, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);

                    if($branchID) $this->repo->fixCommit($repoID);
                }

                if(empty($branchID))
                {
                    $this->repo->markSynced($repoID);
                    return print($this->config->repo->repoSyncLog->finish);
                }
            }
        }

        $this->dao->update(TABLE_REPO)->set('commits=commits + ' . $commitCount)->where('id')->eq($repoID)->exec();
        echo $type == 'batch' ?  $commitCount : $this->config->repo->repoSyncLog->finish;
    }

    /**
     * Ajax sync git branch comment.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function ajaxSyncBranchCommit($repoID = 0, $branch = '')
    {
        set_time_limit(0);
        $repo = $this->repo->getByID($repoID);
        if(empty($repo)) return;
        if(!in_array($repo->SCM, $this->config->repo->gitTypeList)) return print('finish');
        if($branch) $branch = base64_decode(helper::safe64Decode($branch));

        $this->scm->setEngine($repo);

        $this->repo->setRepoBranch($branch);
        helper::setcookie("syncBranch", $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);

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
     * Ajax show side logs.
     *
     * @param  int    $repoID
     * @param  string $path
     * @param  int    $objectID
     * @param  string $type
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function ajaxSideCommits($repoID, $path, $objectID = 0,  $type = 'dir', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        if($this->get->repoPath) $path = $this->get->repoPath;
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $repo      = $this->repo->getByID($repoID);
        $path      = $this->repo->decodePath($path);
        $revisions = $this->repo->getCommits($repo, $path, 'HEAD', $type, $pager);

        $this->view->repo       = $this->repo->getByID($repoID);
        $this->view->revisions  = $revisions;
        $this->view->pager      = $pager;
        $this->view->repoID     = $repoID;
        $this->view->objectID   = $objectID;
        $this->view->logType    = $type;
        $this->view->path       = urldecode($path);
        $this->display();
    }

    /**
     * Ajax get svn tags
     *
     * @param  int    $repoID
     * @param  string $path
     * @access public
     * @return void
     */
    public function ajaxGetSVNDirs($repoID, $path = '')
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

        return print(json_encode($dirs));
    }

    /**
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
     * Create new product select options by remove shadow products if remove project.
     *
     * @access public
     * @return void
     */
    public function ajaxFilterShadowProducts()
    {
        $postData = fixer::input('post')
            ->setDefault('products', array())
            ->setDefault('projectID', 0)
            ->setDefault('objectID', 0)
            ->get();

        $shadowProduct    = $this->loadModel('product')->getShadowProductByProject($postData->projectID);
        $selectedProducts = array_diff($postData->products, array($shadowProduct->id)); // Remove shadow product.

        $products           = $postData->objectID ? $this->loadModel('product')->getProductPairsByProject($objectID) : $this->loadModel('product')->getPairs();
        $linkedProducts     = $this->loadModel('product')->getByIdList($postData->products);
        $linkedProductPairs = array_combine(array_keys($linkedProducts), helper::arrayColumn($linkedProducts, 'name'));
        $products           = $products + $linkedProductPairs;

        return print (html::select('product[]', $products, $selectedProducts, "class='form-control chosen' multiple"));
    }

    /**
     * Get projects list by product id list by ajax.
     *
     * @access public
     * @return void
     */
    public function ajaxProjectsOfProducts()
    {
        $postData = fixer::input('post')
            ->setDefault('products', array())
            ->setDefault('projects', array())
            ->get();
        $productIds = $postData->products ? explode(',', $postData->products) : array();
        $projectIds = $postData->projects ? explode(',', $postData->projects) : array();

        if(empty($productIds))
        {
            $products   = $this->loadModel('product')->getPairs('', 0, '', 'all');
            $productIds = array_keys($products);
        }
        /* Get all projects that can be accessed. */
        $accessProjects = $this->loadModel('product')->getProjectPairsByProductIDList($productIds);

        $selectedProjects = array_intersect(array_keys($accessProjects), $projectIds);

        $options = array();
        foreach($accessProjects as $projectID => $project)
        {
            $options[] = array('text' => $project, 'value' => $projectID);
        }
        return print(json_encode($options));
        $name = isset($postData->number) ? "projects[{$postData->number}][]" : 'projects[]';
        return print (html::select($name, $accessProjects, $selectedProjects, "class='form-control chosen' multiple"));
    }

    /**
     * Ajax get hosts.
     *
     * @param  int    $scm
     * @access public
     * @return void
     */
    public function ajaxGetHosts($scm)
    {
        $scm   = strtolower($scm);
        $hosts = $this->loadModel($scm)->getPairs();

        $options = array();
        foreach($hosts as $hostID => $host)
        {
            $options[] = array('text' => $host, 'value' => $hostID);
        }
        return print(json_encode($options));
    }

    /**
     * Ajax get projects by server.
     *
     * @param  int    $serverID
     * @access public
     * @return void
     */
    public function ajaxGetProjects($serverID)
    {
        $server         = $this->loadModel('pipeline')->getByID($serverID);
        $getProjectFunc = 'ajaxGet' . $server->type . 'Projects';

        $this->$getProjectFunc($serverID);
    }

    /**
     * Ajax get gitea projects.
     *
     * @param  string $gitlabID
     * @param  string $projectIdList
     * @access public
     * @return void
     */
    public function ajaxGetGiteaProjects($giteaID)
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
     * Ajax get gogs projects.
     *
     * @param  string $gitlabID
     * @param  string $projectIdList
     * @access public
     * @return void
     */
    public function ajaxGetGogsProjects($gogsID)
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
     * Ajax get gitlab projects.
     *
     * @param  string $gitlabID
     * @param  string $token
     * @access public
     * @return void
     */
    public function ajaxGetGitlabProjects($gitlabID, $projectIdList = '', $filter = '')
    {
        $projects = $this->repo->getGitlabProjects($gitlabID, $projectIdList, $filter);

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
     * Ajax get branch drop menu.
     *
     * @param  int    $repoID
     * @param  string $branchID
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function ajaxGetBranchDropMenu($repoID, $branchID, $objectID)
    {
        $repo     = $this->repo->getByID($repoID);
        $branches = $this->repo->getBranches($repo);
        if($branchID) $branchID = base64_decode($branchID);

        $branchesHtml = "<div class='table-row'><div class='table-col col-left'><div class='list-group' style='margin-bottom: 0;'>";
        foreach($branches as $id => $branchName)
        {
            $selected = $id == $branchID ? 'selected' : '';
            $branchesHtml .= html::a($this->createLink('repo', 'browse', "repoID=$repoID&branchID=$branchID&objectID=$objectID"), $branchName, '', "class='$selected' data-app='{$this->app->tab}'");
        }
        $branchesHtml .= '</div></div></div>';

        return print($branchesHtml);
    }

    /**
     * Ajax:: Load product by repoID.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxLoadProducts($repoID)
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
    public function ajaxGetExecutions($productID, $branch = 0)
    {
        $executions = $this->repo->getExecutionPairs($productID, $branch);
        echo html::select('execution', $executions, '', 'class="form-control chosen"');
    }

    /**
     * Download zip code.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function downloadCode($repoID = 0, $branch = '')
    {
        $savePath = $this->app->getDataRoot() . 'repo';
        if(!is_dir($savePath))
        {
            if(!is_writable($this->app->getDataRoot())) return print(js::alert(sprintf($this->lang->repo->error->noWritable, dirname($savePath))) . js::close());
            mkdir($savePath, 0777, true);
        }

        $repo = $this->repo->getByID($repoID);
        $this->scm = $this->app->loadClass('scm');
        $this->scm->setEngine($repo);
        $url = $this->scm->getDownloadUrl($branch, $savePath);

        $this->locate($url);
    }

    /**
     * Ajax get branches and tags.
     *
     * @param  int    $repoID
     * @param  string $oldRevision
     * @param  string $newRevision
     * @access public
     * @return void
     */
    public function ajaxGetBranchesAndTags($repoID, $oldRevision = '0', $newRevision = 'HEAD')
    {
        $branchesAndTags = $this->repo->getBranchesAndTags($repoID, $oldRevision, $newRevision);
        return print(json_encode($branchesAndTags));
    }

    /**
     * Get file tree by ajax.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return string
     */
    public function ajaxGetFileTree($repoID, $branch = '')
    {
        $branch = base64_decode($branch);
        $repo   = $this->repo->getByID($repoID);
        $files  = $this->repo->getFileTree($repo, $branch);
        return print($files);
    }

    /**
     * Ajax sync rename record.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxSyncRenameRecord($repoID)
    {
        $this->repo->insertDeleteRecord($repoID);
    }

    /**
     * Get relation by commit.
     *
     * @param  int    $repoID
     * @param  string $commit
     * @access public
     * @return object
     */
    public function ajaxGetCommitRelation($repoID, $commit)
    {
        $titleList = $this->repo->getRelationByCommit($repoID, $commit);
        return $this->send(array('titleList' => $titleList));
    }

    /**
     * Get relation story, task, bug info.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function ajaxGetRelationInfo($objectID, $objectType = 'story')
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
     * Ajax get commit info.
     *
     * @access public
     * @return void
     */
    public function ajaxGetCommitInfo()
    {
        $line       = $this->post->line;
        $repo       = $this->repo->getByID($this->post->repoID);
        $entry      = $this->repo->decodePath($this->post->entry);
        $revision   = $this->post->revision;
        $returnType = $this->post->returnType ? $this->post->returnType : 'view';

        $this->scm->setEngine($repo);
        $blames = $this->scm->blame($entry, $this->post->revision);
        if(!$blames) $blames =$this->scm->blame($entry, $this->post->sourceRevision);

        while($line > 0)
        {
            if(isset($blames[$line]['revision']))
            {
                $revision = $blames[$line]['revision'];
                break;
            }
            $line--;
        }
        if($returnType == 'json') return $this->send(array('result' => 'success', 'blames' => $blames));

        $commits = $this->scm->getCommits($revision, 1);
        if(!empty($commits['commits'][$revision]))
        {
            $commit = $commits['commits'][$revision];

            $objects = $this->repo->getLinkedObjects($commit->comment);
            $stories = $this->dao->select('id,title')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('id')->in($objects['stories'])->fetchPairs();
            $tasks   = $this->dao->select('id,name')->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('id')->in($objects['tasks'])->fetchPairs();
            $bugs    = $this->dao->select('id,title')->from(TABLE_BUG)->where('deleted')->eq(0)->andWhere('id')->in($objects['bugs'])->fetchPairs();

            $this->view->repoID  = $this->post->repoID;
            $this->view->commit  = $commit;
            $this->view->stories = $stories;
            $this->view->tasks   = $tasks;
            $this->view->bugs    = $bugs;
            $this->display();
        }
        else
        {
            echo '';
        }
    }

    /**
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

        if($repo->SCM == 'gitlab') return print(json_encode($this->repo->getGitlabFilesByPath($repo, $path, $branch)));
        return print(json_encode($this->repoZen->getViewTree($repo, $path, $branch)));
    }
}
