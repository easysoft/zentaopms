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
    public function commonAction($repoID = 0, $objectID = 0)
    {
        $tab = $this->app->tab;
        $this->repos = $this->repo->getRepoPairs($tab, $objectID);

        if($tab == 'project')
        {
            $this->loadModel('project')->setMenu($objectID);
        }
        else if($tab == 'execution')
        {
            $this->loadModel('execution')->setMenu($objectID);
        }
        else
        {
            $this->repo->setMenu($this->repos, $repoID);
        }

        if(empty($this->repos) and $this->methodName != 'create') return print($this->locate($this->repo->createLink('create', "objectID=$objectID")));
    }

    /**
     * List all repo.
     *
     * @param  int    $objectID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function maintain($objectID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $repoID = $this->repo->saveState(0, $objectID);
        if($this->viewType !== 'json') $this->commonAction($repoID, $objectID);

        $repoList      = $this->repo->getList(0, '', $orderBy, null, true);
        $sonarRepoList = $this->loadModel('job')->getSonarqubeByRepo(array_keys($repoList));

        /* Pager. */
        $this->app->loadClass('pager', $static = true);
        $recTotal = count($repoList);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $repoList = array_chunk($repoList, $pager->recPerPage);
        $repoList = empty($repoList) ? $repoList : $repoList[$pageID - 1];

        /* Get success jobs of sonarqube.*/
        $jobIDList = array();
        foreach($repoList as $repo)
        {
            if(isset($sonarRepoList[$repo->id])) $jobIDList[] = $sonarRepoList[$repo->id]->id;
        }
        $successJobs = $this->loadModel('compile')->getSuccessJobs($jobIDList);

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->browse;
        $this->view->position[] = $this->lang->repo->common;
        $this->view->position[] = $this->lang->repo->browse;

        $this->view->orderBy       = $orderBy;
        $this->view->objectID      = $objectID;
        $this->view->pager         = $pager;
        $this->view->repoList      = $repoList;
        $this->view->products      = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $this->view->sonarRepoList = $sonarRepoList;
        $this->view->successJobs   = $successJobs;

        $this->display();
    }

    /**
     * Create a repo.
     *
     * @param  int    $objectID  projectID|executionID
     * @access public
     * @return void
     */
    public function create($objectID = 0)
    {
        if($_POST)
        {
            $repoID = $this->repo->create();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('repo', $repoID, 'created');
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $repoID));
            $link = $this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID", '', false) . '#app=' . $this->app->tab;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        $repoID = $this->repo->saveState(0, $objectID);
        $this->commonAction($repoID, $objectID);

        $this->app->loadLang('action');

        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $products = $this->loadModel('product')->getProductPairsByProject($objectID);
        }
        else
        {
            $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        }

        $this->view->title           = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->create;
        $this->view->position[]      = $this->lang->repo->create;
        $this->view->groups          = $this->loadModel('group')->getPairs();
        $this->view->users           = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->view->products        = $products;
        $this->view->projects        = $this->loadModel('product')->getProjectPairsByProductIDList(array_keys($products));
        $this->view->relatedProjects = ($this->app->tab == 'project' or $this->app->tab == 'execution') ? array($objectID) : array();
        $this->view->serviceHosts    = $this->loadModel('gitlab')->getPairs();
        $this->view->objectID        = $objectID;

        $this->display();
    }

    /**
     * Edit a repo.
     *
     * @param  int $repoID
     * @param  int $objectID
     * @access public
     * @return void
     */
    public function edit($repoID, $objectID = 0)
    {
        $this->commonAction($repoID, $objectID);

        $repo = $this->repo->getRepoByID($repoID);
        if($_POST)
        {
            $noNeedSync = $this->repo->update($repoID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $newRepo  = $this->repo->getRepoByID($repoID);
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

        $this->app->loadLang('action');

        $scm = strtolower($repo->SCM);
        if(in_array($scm, $this->config->repo->gitServiceList))
        {
            $serviceID = isset($repo->gitService) ? $repo->gitService : 0;
            $projects  = $this->loadModel($scm)->apiGetProjects($serviceID);
            $options   = array();
            foreach($projects as $project)
            {
                if($scm == 'gitlab') $options[$project->id] = $project->name_with_namespace;
                if($scm == 'gitea')  $options[$project->full_name] = $project->full_name;
                if($scm == 'gogs')   $options[$project->full_name] = $project->full_name;
            }

            $this->view->projects = $options;
        }

        $products           = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $linkedProducts     = $this->loadModel('product')->getByIdList($repo->product);
        $linkedProductPairs = array_combine(array_keys($linkedProducts), array_column($linkedProducts, 'name'));
        $products           = $products + $linkedProductPairs;

        $this->view->title           = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->edit;
        $this->view->repo            = $repo;
        $this->view->repoID          = $repoID;
        $this->view->objectID        = $objectID;
        $this->view->groups          = $this->loadModel('group')->getPairs();
        $this->view->users           = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->view->products        = $products;
        $this->view->relatedProjects = $this->repo->filterProject(explode(',', $repo->product), explode(',', $repo->projects));
        $this->view->serviceHosts    = array('' => '') + $this->loadModel('pipeline')->getPairs($repo->SCM);

        $this->view->position[] = html::a(inlink('maintain'), $this->lang->repo->common);
        $this->view->position[] = $this->lang->repo->edit;

        $this->display();
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
    public function delete($repoID, $objectID = 0, $confirm = 'no')
    {
        if($confirm == 'no') return print(js::confirm($this->lang->repo->notice->delete, $this->repo->createLink('delete', "repoID=$repoID&objectID=$objectID&confirm=yes")));

        $relationID = $this->dao->select('id')->from(TABLE_RELATION)
            ->where('extra')->eq($repoID)
            ->andWhere('AType')->eq('design')
            ->fetch();

        $error = $relationID ? $this->lang->repo->error->deleted : '';

        $jobs = $this->dao->select('*')->from(TABLE_JOB)->where('repo')->eq($repoID)->andWhere('deleted')->eq('0')->fetchAll();
        if($jobs) $error .= ($error ? '\n' : '') . $this->lang->repo->error->linkedJob;

        if($error) return print(js::alert($error));

        $this->repo->delete(TABLE_REPO, $repoID);
        if(dao::isError()) return print(js::error(dao::getError()));
        return print(js::reload('parent'));
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
        $file      = $entry;
        $repo      = $this->repo->getRepoByID($repoID);
        $entry     = urldecode($this->repo->decodePath($entry));
        $revision  = str_replace('*', '-', $oldRevision);
        $nRevision = str_replace('*', '-', $newRevision);

        $entry    = urldecode($entry);
        $pathInfo = pathinfo($entry);
        $encoding = empty($encoding) ? $repo->encoding : $encoding;
        $encoding = strtolower(str_replace('_', '-', $encoding));

        $this->scm->setEngine($repo);
        $info = $this->scm->info($entry, $nRevision);
        $path = $entry ? $info->path : '';

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
        $this->display('repo', 'ajaxGetEditorContent');
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
        $file     = $entry;
        $repo     = $this->repo->getRepoByID($repoID);
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
        $file     = $entry;
        $repo     = $this->repo->getRepoByID($repoID);
        $entry    = $this->repo->decodePath($entry);
        $entry    = urldecode($entry);
        $pathInfo = pathinfo($entry);

        $this->view->title    = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->view;
        $this->view->type     = 'view';
        $this->view->branchID = $this->cookie->repoBranch;
        $this->view->showBug  = $showBug;
        $this->view->encoding = $encoding;
        $this->view->repoID   = $repoID;
        $this->view->objectID = $objectID;
        $this->view->repo     = $repo;
        $this->view->revision = $revision;
        $this->view->file     = $file;
        $this->view->entry    = $entry;
        $this->view->pathInfo = $pathInfo;
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
        $browser = helper::getBrowser();
        if($this->get->repoPath) $entry = $this->get->repoPath;
        $this->repo->setBackSession('view', $withOtherModule = true);
        if($repoID == 0) $repoID = $this->session->repoID;
        if($revision != 'HEAD')
        {
            setCookie("repoBranch", $revision, $this->config->cookieLife, $this->config->webRoot, '', false, true);
            $this->cookie->set('repoBranch', $revision);
        }

        $this->commonAction($repoID, $objectID);
        if($browser['name'] != 'ie') return print($this->fetch('repo', 'monaco', "repoID=$repoID&objectID=$objectID&entry=$entry&revision=$revision&showBug=$showBug&encoding=$encoding"));

        if($_POST)
        {
            $oldRevision = isset($this->post->revision[1]) ? $this->post->revision[1] : '';
            $newRevision = isset($this->post->revision[0]) ? $this->post->revision[0] : '';

            $this->locate($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=$entry&oldrevision=$oldRevision&newRevision=$newRevision"));
        }

        $file     = $entry;
        $repo     = $this->repo->getRepoByID($repoID);
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

        $this->app->loadClass('pager', $static = true);
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
        $this->view->position[] = $this->lang->repo->common;
        $this->view->position[] = $this->lang->repo->view;
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
    public function browse($repoID = 0, $branchID = '', $objectID = 0, $path = '', $revision = 'HEAD', $refresh = 0, $branchOrTag = 'branch')
    {
        $repoID                 = $this->repo->saveState($repoID, $objectID);
        $originBranchID         = $branchID;
        if($branchID) $branchID = base64_decode(helper::safe64Decode($branchID));

        /* Get path and refresh. */
        if($this->get->repoPath) $path = $this->get->repoPath;
        if(empty($refresh) and $this->cookie->repoRefresh) $refresh = $this->cookie->repoRefresh;

        /* Set menu and session. */
        $this->commonAction($repoID, $objectID);
        $this->repo->setBackSession('list', $withOtherModule = true);

        session_start();
        $this->session->set('revisionList', $this->app->getURI(true));
        $this->session->set('gitlabBranchList', $this->app->getURI(true));
        session_write_close();

        /* Get repo and synchronous commit. */
        $repo = $this->repo->getRepoByID($repoID);
        if($repo->SCM == 'Git' and !is_dir($repo->path))
        {
            $error = sprintf($this->lang->repo->error->notFound, $repo->name, $repo->path);
            return print(js::error($error) . js::locate($this->repo->createLink('maintain')));
        }
        if(!$repo->synced) $this->locate($this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID"));

        /* Set branch or tag for git. */
        $branches = $tags = $branchesAndTags = array();
        if(in_array($repo->SCM, $this->config->repo->gitTypeList))
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $branches = $scm->branch();
            $initTags = $scm->tags('');
            foreach($initTags as $tag) $tags[$tag] = $tag;
            $branchesAndTags = $branches + $tags;

            if(empty($branchID) and $this->cookie->repoBranch and $this->session->repoID == $repoID) $branchID = $this->cookie->repoBranch;
            if($branchID) $this->repo->setRepoBranch($branchID);
            if(!isset($branchesAndTags[$branchID]))
            {
                $branchID = key($branches);
                $this->repo->setRepoBranch($branchID);
            }
        }
        else
        {
            $this->repo->setRepoBranch('');
        }

        /* Decrypt path. */
        $path = $this->repo->decodePath($path);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 10, 1);

        if($_POST)
        {
            $oldRevision = isset($this->post->revision[1]) ? $this->post->revision[1] : '';
            $newRevision = isset($this->post->revision[0]) ? $this->post->revision[0] : '';

            $this->locate($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($path) . "&oldrevision=$oldRevision&newRevision=$newRevision"));
        }

        /* Refresh repo. */
        if($refresh)
        {
            $this->repo->updateCommit($repoID, $objectID, $originBranchID);

            if($repo->SCM == 'Gitlab') $this->repo->checkDeletedBranches($repoID, $branches);
        }

        /* Set logType and revisions. */
        $logType      = 'dir';
        $revisions    = $this->repo->getCommits($repo, $path, $revision, $logType, $pager);
        $lastRevision = current($revisions);

        /* Get files info. */
        if($repo->SCM == 'Gitlab')
        {
            $cacheFile        = $this->repo->getCacheFile($repo->id, $path, $branchID);
            $cacheRefreshTime = isset($lastRevision->time) ? date('Y-m-d H:i', strtotime($lastRevision->time)) : date('Y-m-d H:i');
            if(!$cacheFile or !file_exists($cacheFile) or filemtime($cacheFile) < strtotime($cacheRefreshTime))
            {
                $infos = $this->repo->getFileList($repo, $branchID, $path);

                if($cacheFile)
                {
                    if(!file_exists($cacheFile . '.lock'))
                    {
                        touch($cacheFile . '.lock');
                        file_put_contents($cacheFile, serialize($infos));
                        unlink($cacheFile . '.lock');
                    }
                }
            }
            else
            {
                $infos = unserialize(file_get_contents($cacheFile));
            }
        }
        else
        {
            $infos = $this->repo->getFileCommits($repo, $branchID, $path);
        }
        if($this->cookie->repoRefresh) setcookie('repoRefresh', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);

        /* Synchronous commit only in root path. */
        if(in_array($repo->SCM, $this->config->repo->gitTypeList) and empty($path) and $infos and empty($revisions)) $this->locate($this->repo->createLink('showSyncCommit', "repoID=$repoID&objectID=$objectID&branch=" . helper::safe64Encode(base64_encode($this->cookie->repoBranch))));

        $this->view->title           = $this->lang->repo->common;
        $this->view->repo            = $repo;
        $this->view->repos           = $this->repos;
        $this->view->revisions       = $revisions;
        $this->view->repoGroup       = $this->repo->getRepoGroup($this->app->tab, $objectID);
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
        $this->view->logType         = $logType;
        $this->view->cloneUrl        = $this->repo->getCloneUrl($repo);
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
        $this->repo->setBackSession('log', $withOtherModule = true);
        if($repoID == 0) $repoID = $this->session->repoID;

        $repo  = $this->repo->getRepoByID($repoID);
        $file  = $entry;
        $entry = $this->repo->decodePath($entry);

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

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
    public function revision($repoID, $objectID = 0, $revision = '', $root = '', $type = 'dir')
    {
        $this->repo->setBackSession();
        if($repoID == 0) $repoID = $this->session->repoID;
        $repo = $this->repo->getRepoByID($repoID);

        /* Save session. */
        $this->session->set('revisionList', $this->app->getURI(true), 'repo');

        $this->commonAction($repoID, $objectID);
        $this->scm->setEngine($repo);
        $log = $this->scm->log('', $revision, $revision);
        $log[0]->comment = $this->repo->replaceCommentLink($log[0]->comment);
        $log[0]->commit  = '';

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

            $log[0]->commit = $history->commit;
        }

        if(empty($oldRevision))
        {
            $oldRevision = '^';
            if($history and in_array($repo->SCM, $this->config->repo->gitTypeList)) $oldRevision = "{$history->revision}^";
        }

        $changes  = array();
        $viewPriv = common::hasPriv('repo', 'view');
        $diffPriv = common::hasPriv('repo', 'diff');
        foreach($log[0]->change as $path => $change)
        {
            if($repo->prefix) $path = str_replace($repo->prefix, '', $path);
            $encodePath = $this->repo->encodePath($path);
            if($change['kind'] == '' or $change['kind'] == 'file')
            {
                $change['view'] = $viewPriv ? html::a($this->repo->createLink('view', "repoID=$repoID&objectID=$objectID&entry=$encodePath&revision=$revision"), $this->lang->repo->viewA, '', "data-app='{$this->app->tab}'") : '';
                if($change['action'] == 'M') $change['diff'] = $diffPriv ? html::a($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=$encodePath&oldRevision=$oldRevision&newRevision=$revision"), $this->lang->repo->diffAB, '', "data-app='{$this->app->tab}'") : '';
            }
            else
            {
                $change['view'] = $viewPriv ? html::a($this->repo->createLink('browse', "repoID=$repoID&branchID=&objectID=$objectID&path=$encodePath&revision=$revision"), $this->lang->repo->browse, '', "data-app='{$this->app->tab}'") : '';
                if($change['action'] == 'M') $change['diff'] = $diffPriv ? html::a($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=$encodePath&oldRevision=$oldRevision&newRevision=$revision"), $this->lang->repo->diffAB, '', "data-app='{$this->app->tab}'") : '';
            }
            $changes[$path] = $change;
        }

        $root   = $this->repo->decodePath($root);
        $parent = '';
        if($type == 'file')
        {
            $parent = $this->dao->select('parent')->from(TABLE_REPOFILES)
                ->where('revision')->eq($history->id)
                ->andWhere('path')->eq('/' . $root)
                ->fetch('parent');
        }

        $this->view->title       = $this->lang->repo->common;
        $this->view->log         = $log[0];
        $this->view->repo        = $repo;
        $this->view->path        = $root;
        $this->view->type        = $type;
        $this->view->changes     = $changes;
        $this->view->repoID      = $repoID;
        $this->view->branchID    = $this->cookie->repoBranch;
        $this->view->objectID    = $objectID;
        $this->view->revision    = $log[0]->revision;
        $this->view->parentDir   = $parent;
        $this->view->oldRevision = $oldRevision;
        $this->view->preAndNext  = $this->repo->getPreAndNext($repo, $root, $revision, $type, 'revision');

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->viewRevision;
        $this->view->position[] = $this->lang->repo->common;
        $this->view->position[] = $this->lang->repo->viewRevision;

        $this->display();
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
        $repo  = $this->repo->getRepoByID($repoID);
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
        $oldRevision = urldecode(urldecode($oldRevision)); //Fix error.

        $this->commonAction($repoID, $objectID);

        if($this->get->repoPath) $entry = $this->get->repoPath;
        $file  = $entry;
        $repo  = $this->repo->getRepoByID($repoID);
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
                setcookie('arrange', $arrange);
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
        $this->view->branchID    = $this->cookie->repoBranch;
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

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->diff;
        $this->view->position[] = $this->lang->repo->common;
        $this->view->position[] = $this->lang->repo->diff;

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
        $repo  = $this->repo->getRepoByID($repoID);

        if($isBranchOrTag)
        {
            $fromRevision = urldecode(helper::safe64Decode($fromRevision));
            $toRevision   = urldecode(helper::safe64Decode($toRevision));
        }

        $this->commonAction($repoID);
        $this->scm->setEngine($repo);
        $content = $type == 'file' ? $this->scm->cat($entry, $fromRevision) : $this->scm->diff($entry, $fromRevision, $toRevision, 'patch', $isBranchOrTag ? 'isBranchOrTag': '');

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
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('setRules')));
        }

        $repoID = $this->session->repoID;
        $this->commonAction($repoID);
        $this->lang->switcherMenu = '';

        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('story');
        if(is_string($this->config->repo->rules)) $this->config->repo->rules = json_decode($this->config->repo->rules, true);

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->setRules;
        $this->view->position[] = $this->lang->repo->setRules;

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
        $this->view->position[] = $this->lang->repo->showSyncCommit;

        $latestInDB = $this->repo->getLatestCommit($repoID);
        $this->view->version    = $latestInDB ? (int)$latestInDB->commit : 1;
        $this->view->repoID     = $repoID;
        $this->view->objectID   = $objectID;
        $this->view->branch     = $branch;
        $this->view->browseLink = $this->repo->createLink('browse', "repoID=" . ($this->app->tab == 'devops' ? $repoID : '') . "&branchID=" . helper::safe64Encode(base64_encode($branch)) . "&objectID=$objectID", '', false);
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
    public function linkStory($repoID, $revision, $browseType = '', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(!empty($_POST['stories']))
        {
            $this->repo->link($repoID, $revision, 'story');

            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::closeModal('parent', '', "parent.parent[parent.parent.length - 2].getRelation('$revision')"));
        }

        $this->loadModel('story');
        $this->app->loadLang('productplan');

        $repo    = $this->repo->getRepoByID($repoID);
        $product = $this->loadModel('product')->getById($repo->product);
        $modules = $this->loadModel('tree')->getOptionMenu($repo->product, 'story');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        unset($this->lang->story->statusList['closed']);
        $storyStatusList = $this->lang->story->statusList;
        $queryID         = $browseType == 'bySearch' ? (int)$param : 0;

        unset($this->config->product->search['fields']['product']);
        $this->config->product->search['actionURL']                   = $this->createLink('repo', 'linkStory', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID");
        $this->config->product->search['queryID']                     = $queryID;
        $this->config->product->search['style']                       = 'simple';
        $this->config->product->search['params']['plan']['values']    = $this->loadModel('productplan')->getForProducts(array($product->id => $product->id));
        $this->config->product->search['params']['module']['values']  = $modules;
        $this->config->product->search['params']['status']            = array('operator' => '=', 'control' => 'select', 'values' => $storyStatusList);

        if($product->type == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->config->product->search['fields']['branch'] = $this->lang->product->branch;
            $this->config->product->search['params']['branch']['values'] = array('' => '') + $this->loadModel('branch')->getPairs($product->id, 'noempty');
        }

        session_start();
        $this->loadModel('search')->setSearchParams($this->config->product->search);
        session_write_close();

        $linkedStories = $this->repo->getRelationByCommit($repoID, $revision, 'story');
        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($product->id, 0, $queryID, 'id', '', 'story', array_keys($linkedStories), $pager);
        }
        else
        {
            $allStories = $this->story->getProductStories($product->id, 0, '0', 'draft,active,changed', 'story', 'id_desc', false, array_keys($linkedStories), $pager);
        }

        $this->view->modules        = $modules;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->allStories     = $allStories;
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
    public function linkBug($repoID, $revision = '', $browseType = '', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(!empty($_POST['bugs']))
        {
            $this->repo->link($repoID, $revision, 'bug');

            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::closeModal('parent', '', "parent.parent[parent.parent.length - 2].getRelation('$revision')"));
        }

        $this->loadModel('bug');
        $this->app->loadLang('productplan');
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;

        $repo    = $this->repo->getRepoByID($repoID);
        $product = $this->loadModel('product')->getById($repo->product);
        $modules = $this->loadModel('tree')->getOptionMenu($product->id, 'bug');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->config->bug->search['actionURL']                         = $this->createLink('repo', 'linkBug', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID");
        $this->config->bug->search['queryID']                           = $queryID;
        $this->config->bug->search['style']                             = 'simple';
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getForProducts(array($product->id => $product->id));
        $this->config->bug->search['params']['module']['values']        = $modules;
        $this->config->bug->search['params']['execution']['values']     = $this->product->getExecutionPairsByProduct($product->id);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs($product->id, $branch = 'all', $params = '');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->loadModel('build')->getBuildPairs($product->id, $branch = 'all', $params = '');

        unset($this->config->bug->search['fields']['product']);
        if($product->type == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->config->bug->search['fields']['branch']           = $this->lang->product->branch;
            $this->config->bug->search['params']['branch']['values'] = array('' => '') + $this->loadModel('branch')->getPairs($product->id, 'noempty');
        }
        session_start();
        $this->loadModel('search')->setSearchParams($this->config->bug->search);
        session_write_close();

        $linkedBugs = $this->repo->getRelationByCommit($repoID, $revision, 'bug');
        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch($product->id, 0, $queryID, 'id_desc', array_keys($linkedBugs), $pager);
        }
        else
        {
            $allBugs = $this->bug->getActiveBugs($product->id, 0, '0', array_keys($linkedBugs), $pager);
        }

        $this->view->modules     = $modules;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->allBugs     = $allBugs;
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
    public function linkTask($repoID, $revision = '', $browseType = 'unclosed', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(!empty($_POST['tasks']))
        {
            $this->repo->link($repoID, $revision, 'task');

            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::closeModal('parent', '', "parent.parent[parent.parent.length - 2].getRelation('$revision')"));
        }

        $this->loadModel('execution');
        $this->loadModel('product');
        $this->app->loadLang('task');

        /* Set browse type. */
        $browseType = strtolower($browseType);
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;

        $repo    = $this->repo->getRepoByID($repoID);
        $product = $this->loadModel('product')->getById($repo->product);
        $modules = $this->loadModel('tree')->getOptionMenu($product->id, $viewType = 'task');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->config->execution->search['actionURL']                     = $this->createLink('repo', 'linkTask', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID", '', true);
        $this->config->execution->search['queryID']                       = $queryID;
        $this->config->execution->search['style']                         = 'simple';
        $this->config->execution->search['params']['module']['values']    = $modules;
        $this->config->execution->search['params']['execution']['values'] = $this->product->getExecutionPairsByProduct($product->id);

        /* Get executions by product. */
        $productExecutions   = $this->product->getExecutionPairsByProduct($product->id);
        $productExecutionIDs = array_filter(array_keys($productExecutions));
        $this->config->execution->search['params']['execution']['values'] = array_filter($productExecutions);

        session_start();
        $this->loadModel('search')->setSearchParams($this->config->execution->search);
        session_write_close();

        /* Get tasks by executions. */
        $allTasks = array();
        foreach($productExecutionIDs as $productExecutionID)
        {
            $tasks    = $this->execution->getTasks(0, $productExecutionID, array(), $browseType, $queryID, 0, $orderBy, null);
            $allTasks = array_merge($tasks, $allTasks);
        }

        /* Filter linked tasks. */
        $linkedTasks   = $this->repo->getRelationByCommit($repoID, $revision, 'task');
        $linkedTaskIDs = array_keys($linkedTasks);
        foreach($allTasks as $key => $task)
        {
            if(in_array($task->id, $linkedTaskIDs)) unset($allTasks[$key]);
        }

        /* Page the records. */
        $pager->setRecTotal(count($allTasks));
        $pager->setPageTotal();
        if($pager->pageID > $pager->pageTotal) $pager->setPageID($pager->pageTotal);
        $count    = 1;
        $limitMin = ($pager->pageID - 1) * $pager->recPerPage;
        $limitMax = $pager->pageID * $pager->recPerPage;
        foreach($allTasks as $key => $task)
        {
            if($count <= $limitMin or $count > $limitMax) unset($allTasks[$key]);
            $count ++;
        }

        $this->view->modules      = $modules;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->allTasks     = $allTasks;
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
     * Import repos.
     *
     * @param int $server
     * @access public
     * @return void
     */
    public function import($server = 0)
    {
        if($this->viewType !== 'json') $this->commonAction();
        if($_POST)
        {
            $this->repo->batchCreate();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->repo->createLink('maintain')));
        }

        $gitlabList = $this->loadModel('gitlab')->getList();
        $gitlab     = empty($server) ? array_shift($gitlabList) : $this->gitlab->getById($server);

        $repoList = array();
        if(!empty($gitlab))
        {
            $repoList      = $this->gitlab->apiGetProjects($gitlab->id);
            $existRepoList = $this->dao->select('serviceProject,name')->from(TABLE_REPO)
                ->where('SCM')->eq(ucfirst($gitlab->type))
                ->andWhere('serviceHost')->eq($gitlab->id)
                ->fetchPairs();
            foreach($repoList as $key => $repo)
            {
                if(isset($existRepoList[$repo->id])) unset($repoList[$key]);
            }
        }

        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');

        $this->view->gitlabPairs = $this->gitlab->getPairs();
        $this->view->products = $products;
        $this->view->projects = $this->product->getProjectPairsByProductIDList(array_keys($products));
        $this->view->gitlab   = $gitlab;
        $this->view->repoList = array_values($repoList);
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
        $repo = $this->repo->getRepoByID($repoID);
        if(empty($repo)) return;
        if($repo->synced) return print('finish');

        if(in_array($repo->SCM, array('Gitea', 'Gogs')))
        {
            $logFile = realPath($this->app->getTmpRoot() . "/log/clone.progress." . strtolower($repo->SCM) . ".{$repo->name}.log");
            if($logFile)
            {
                $content  = file($logFile);
                $lastLine = $content[count($content) - 1];

                if(strpos($lastLine, 'done') === false)
                {
                    if(strpos($lastLine, 'empty repository') !== false)
                    {
                        @unlink($logFile);
                    }
                    elseif(strpos($lastLine, 'Total') !== false)
                    {
                        $logContent = file_get_contents($logFile);
                        if(strpos($logContent, 'Counting objects: 100%') !== false and strpos($logContent, 'Compressing objects: 100%') !== false)
                        {
                            @unlink($logFile);
                        }
                        else
                        {
                            return print(1);
                        }
                    }
                    else
                    {
                        return print(1);
                    }
                }
                elseif(strpos($lastLine, 'fatal') !== false)
                {
                    return print('finish');
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
                setcookie("syncBranch", $branchID, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
            }
        }

        $latestInDB = $this->dao->select('t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
            ->beginIF($repo->SCM == 'Git' and $this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->beginIF($repo->SCM == 'Gitlab' and $this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->orderBy('t1.time')
            ->limit(1)
            ->fetch();

        $version  = empty($latestInDB) ? 1 : $latestInDB->commit + 1;
        $logs     = array();
        $revision = $version == 1 ? 'HEAD' : (in_array($repo->SCM, array('Git', 'Gitea', 'Gogs')) ? $latestInDB->commit : $latestInDB->revision);
        if($type == 'batch')
        {
            $logs = $this->scm->getCommits($revision, $this->config->repo->batchNum, $branchID);
        }
        else
        {
            $logs = $this->scm->getCommits($revision, 0, $branchID);
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
                    return print('finish');
                }
            }
        }

        $this->dao->update(TABLE_REPO)->set('commits=commits + ' . $commitCount)->where('id')->eq($repoID)->exec();
        echo $type == 'batch' ?  $commitCount : 'finish';
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
        $repo = $this->repo->getRepoByID($repoID);
        if(empty($repo)) return;
        if(!in_array($repo->SCM, $this->config->repo->gitTypeList)) return print('finish');
        if($branch) $branch = base64_decode(helper::safe64Decode($branch));

        $this->scm->setEngine($repo);

        $this->repo->setRepoBranch($branch);
        setcookie("syncBranch", $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);

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

            setcookie("syncBranch", $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
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
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $repo      = $this->repo->getRepoByID($repoID);
        $path      = $this->repo->decodePath($path);
        $revisions = $this->repo->getCommits($repo, $path, 'HEAD', $type, $pager);

        $this->view->repo       = $this->repo->getRepoByID($repoID);
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
        $repo = $this->repo->getRepoByID($repoID);
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
    public function ajaxGetDropMenu($repoID, $module = 'repo', $method = 'browse', $projectID = 0)
    {
        if($module == 'repo' and !in_array($method, array('review', 'diff'))) $method = 'browse';
        if($module == 'mr')  $method = 'browse';
        if($module == 'job') $method = 'browse';
        if($module == 'compile' and $method == 'logs') $method = 'browse';
        if($module == 'bug' and $method == 'view')
        {
            $module = 'repo';
            $method = 'review';
        }

        /* Get repo group by type. */
        $repoGroup = $this->repo->getRepoGroup($this->app->tab, $projectID);
        if($module == 'mr')
        {
            foreach($repoGroup as $type => $group)
            {
                if(!in_array(strtolower($type), $this->config->repo->gitServiceList)) unset($repoGroup[$type]);
            }
        }

        $this->view->repoID    = $repoID;
        $this->view->repoGroup = $repoGroup;
        $this->view->link      = $this->createLink($module, $method, "repoID=%s");

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
        $linkedProductPairs = array_combine(array_keys($linkedProducts), array_column($linkedProducts, 'name'));
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

        $productIds = $postData->products;
        if(empty($productIds))
        {
            $products   = $this->loadModel('product')->getPairs('', 0, '', 'all');
            $productIds = array_keys($products);
        }
        /* Get all projects that can be accessed. */
        $accessProjects = $this->loadModel('product')->getProjectPairsByProductIDList($productIds);

        $selectedProjects = array_intersect(array_keys($accessProjects), $postData->projects);

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
        return print(html::select('pipelineHost', $hosts, '', "class='form-control chosen'"));
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
        if(!$projects) $this->send(array('message' => array()));

        $options = "<option value=''></option>";
        foreach($projects as $project) $options .= "<option value='{$project->full_name}' data-name='{$project->name}'>{$project->full_name}</option>";

        return print($options);
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
        $options = "<option value=''></option>";
        if(!empty($projects))
        {
            foreach($projects as $project) $options .= "<option value='{$project->full_name}' data-name='{$project->name}'>{$project->full_name}</option>";
        }

        return print($options);
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
        $showAll = ($filter == 'ALL' and common::hasPriv('repo', 'create')) ? true : false;
        if($this->app->user->admin or $showAll)
        {
            $projects = $this->loadModel('gitlab')->apiGetProjects($gitlabID, true, 0, 0, false);
        }
        else
        {
            $gitlabUser = $this->loadModel('gitlab')->getUserIDByZentaoAccount($gitlabID, $this->app->user->account);
            if(!$gitlabUser) $this->send(array('message' => array()));

            $projects    = $this->gitlab->apiGetProjects($gitlabID, $filter ? 'false' : 'true');
            $groupIDList = array(0 => 0);
            $groups      = $this->gitlab->apiGetGroups($gitlabID, 'name_asc', 'developer');
            foreach($groups as $group) $groupIDList[] = $group->id;
            if($filter == 'IS_DEVELOPER')
            {
                foreach($projects as $key => $project)
                {
                    if($this->gitlab->checkUserAccess($gitlabID, 0, $project, $groupIDList, 'developer') == false) unset($projects[$key]);
                }
            }
        }

        if(!$projects) $this->send(array('message' => array()));
        $projectIdList = $projectIdList ? explode(',', $projectIdList) : null;
        $options = "<option value=''></option>";
        foreach($projects as $project)
        {
            if(!empty($projectIdList) and $project and !in_array($project->id, $projectIdList)) continue;
            $options .= "<option value='{$project->id}' data-name='{$project->name}'>{$project->name_with_namespace}</option>";
        }

        return print($options);
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
        $repo     = $this->repo->getRepoByID($repoID);
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
        echo html::select('product', array('') + $productPairs, key($productPairs), "class='form-control chosen'");
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
        echo html::select('execution', array('' => '') + $executions, '', 'class="form-control chosen"');
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
        $repo     = $this->repo->getRepoByID($repoID);
        $savePath = $this->app->getDataRoot() . 'repo';
        if(!is_dir($savePath))
        {
            if(!is_writable($this->app->getDataRoot())) return print(js::alert(sprintf($this->lang->repo->error->noWritable, dirname($savePath))) . js::close());
            mkdir($savePath, 0777, true);
        }

        $repo = $this->repo->getRepoByID($repoID);
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
        $repo   = $this->repo->getRepoByID($repoID);
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
        $repo       = $this->repo->getRepoByID($this->post->repoID);
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
}
