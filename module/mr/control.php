<?php
declare(strict_types=1);
/**
 * The control file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
class mr extends control
{
    /**
     * The gitlab constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        /* This is essential when changing tab(menu) from gitlab to repo. */
        /* Optional: common::setMenuVars('devops', $this->session->repoID); */
        if($this->app->getMethodName() != 'browse')
        {
            $this->loadModel('ci')->setMenu();
            $this->view->objectID = $this->app->tab == 'execution' ? $this->session->execution : 0;
            if($this->app->tab == 'execution')
            {
                $this->view->executionID = $this->session->execution;
                $this->loadModel('execution')->setMenu($this->session->execution);
            }
        }
    }

    /**
     * 获取合并请求列表.
     * Browse mr.
     *
     * @param  int    $repoID
     * @param  string $mode
     * @param  string $param
     * @param  int    $objectID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $repoID = 0, string $mode = 'status', string $param = 'opened', int $objectID = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        if($this->app->tab == 'execution')
        {
            $this->session->set('execution', $objectID);
            $execution = $this->loadModel('execution')->getByID($objectID);
            $features = $this->execution->getExecutionFeatures($execution);
            if(!$features['devops']) return $this->locate($this->createLink('execution', 'task', "objectID=$executionID"));

            $this->loadModel('execution')->setMenu($objectID);
        }

        if($this->app->tab == 'execution' && $objectID) return print($this->fetch('mr', 'browseByExecution', "repoID={$repoID}&mode={$mode}&param={$param}&objectID={$objectID}&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}"));

        $repoList = $this->loadModel('repo')->getListBySCM('Gitlab,Gitea,Gogs');
        if(empty($repoList)) $this->locate($this->repo->createLink('create'));

        if(!isset($repoList[$repoID])) $repoID = key($repoList);
        $repoID = $this->repo->saveState($repoID, $objectID);
        $repo   = $repoList[$repoID];
        $this->loadModel('ci')->setMenu($repo->id);

        if($param == 'assignee' || $param == 'creator')
        {
            $mode  = $param;
            $param = $this->app->user->account;
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $filterProjects = empty($repo->serviceProject) ? array() : array($repo->serviceHost => array($repo->serviceProject => $repo->serviceProject));
        $MRList         = $this->mr->getList($mode, $param, $orderBy, $filterProjects, $repoID, 0, $pager);
        $MRList         = $this->mr->batchSyncMR($MRList, array($repoID => $repo));
        $projects       = $this->mrZen->getAllProjects($repo, $MRList);

        /* Check whether Mr is linked with the product. */
        foreach($MRList as $MR)
        {
            $product         = $this->mr->getMRProduct($MR);
            $MR->linkButton  = empty($product) ? false : true;
        }

        $this->view->title      = $this->lang->mr->common . $this->lang->colon . $this->lang->mr->browse;
        $this->view->MRList     = $MRList;
        $this->view->projects   = $projects;
        $this->view->pager      = $pager;
        $this->view->mode       = $mode;
        $this->view->param      = $param;
        $this->view->objectID   = $objectID;
        $this->view->repo       = $repo;
        $this->view->orderBy    = $orderBy;
        $this->view->openIDList = $this->app->user->admin ? array() : $this->loadModel(strtolower($repo->SCM))->getListByAccount($this->app->user->account);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 获取执行下合并请求列表.
     * Browse mr for execution.
     *
     * @param  int    $repoID
     * @param  string $mode
     * @param  string $param
     * @param  int    $executionID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseByExecution(int $repoID = 0, string $mode = 'status', string $param = 'opened', int $executionID = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        if($param == 'assignee' || $param == 'creator')
        {
            $mode  = $param;
            $param = $this->app->user->account;
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $MRList   = $this->mr->getList($mode, $param, $orderBy, array(), $repoID, $executionID, $pager);
        $repoList = $this->loadModel('repo')->getList($executionID);
        $MRList   = $this->mr->batchSyncMR($MRList, $repoList);

        $projects  = array();
        $repoPairs = array();
        foreach($repoList as $repo)
        {
            if(!in_array($repo->SCM, $this->config->repo->gitServiceTypeList)) continue;

            $repoPairs[$repo->id] = $repo->name;
            if($repoID && $repoID != $repo->id) continue;

            $projects += $this->mrZen->getAllProjects($repo, $MRList);
        }

        $openIDList = array();
        if(!$this->app->user->admin)
        {
            $openIDList += $this->loadModel('gitlab')->getListByAccount($this->app->user->account);
            $openIDList += $this->loadModel('gitea')->getListByAccount($this->app->user->account);
            $openIDList += $this->loadModel('gogs')->getListByAccount($this->app->user->account);
        }

        $this->view->title       = $this->lang->mr->common . $this->lang->colon . $this->lang->mr->browse;
        $this->view->MRList      = $MRList;
        $this->view->projects    = $projects;
        $this->view->pager       = $pager;
        $this->view->mode        = $mode;
        $this->view->repoID      = $repoID;
        $this->view->param       = $param;
        $this->view->objectID    = $executionID;
        $this->view->executionID = $executionID;
        $this->view->repoList    = $repoList;
        $this->view->repoPairs   = $repoPairs;
        $this->view->orderBy     = $orderBy;
        $this->view->openIDList  = $openIDList;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 创建合并请求.
     * Create MR function.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function create(int $repoID = 0, int $objectID = 0)
    {
        if($_POST)
        {
            $result = $this->mr->create();
            return $this->send($result);
        }

        $repoID = $this->loadModel('repo')->saveState($repoID);
        $repo   = $this->repo->getByID($repoID);

        $project = $this->loadModel(strtolower($repo->SCM))->apiGetSingleProject($repo->gitService, $repo->serviceProject, false);

        $jobPairs = array();
        $jobs     = $this->loadModel('job')->getListByRepoID($repoID);
        foreach($jobs as $job) $jobPairs[$job->id] = "[{$job->id}]{$job->name}";

        $repoPairs = array();
        if($this->app->tab == 'execution' && $objectID)
        {
            $repoList = $this->loadModel('repo')->getList($objectID);
            foreach($repoList as $repoInfo)
            {
                if(in_array($repo->SCM, $this->config->repo->gitServiceTypeList)) $repoPairs[$repoInfo->id] = $repoInfo->name;
            }
        }

        $this->view->title       = $this->lang->mr->create;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->repo        = $repo;
        $this->view->repoPairs   = $repoPairs;
        $this->view->project     = $project;
        $this->view->executionID = $objectID;
        $this->view->objectID    = $objectID;
        $this->view->jobPairs    = $jobPairs;
        $this->display();
    }

    /**
     * 编辑合并请求。
     * Edit MR function.
     *
     * @param  int    $MRID
     * @access public
     * @return void
     */
    public function edit(int $MRID)
    {
        if($_POST)
        {
            $result = $this->mr->update($MRID);
            return $this->send($result);
        }

        $MR = $this->mr->getByID($MRID);
        if(isset($MR->hostID)) $rawMR = $this->mr->apiGetSingleMR($MR->hostID, $MR->targetProject, $MR->mriid);
        $this->view->title = $this->lang->mr->edit;
        $this->view->MR    = $MR;
        $this->view->rawMR = isset($rawMR) ? $rawMR : false;
        if(!isset($rawMR->id) || empty($rawMR)) return $this->display();

        /* Fetch user list both in Zentao and current GitLab project. */
        $host     = $this->loadModel('pipeline')->getByID($MR->hostID);
        $scm      = $host->type;
        $gitUsers = $this->$scm->getUserAccountIdPairs($MR->hostID);

        /* Check permissions. */
        if(!$this->app->user->admin and $scm == 'gitlab')
        {
            $groupIDList = array(0 => 0);
            $groups      = $this->$scm->apiGetGroups($MR->hostID, 'name_asc', 'developer');
            foreach($groups as $group) $groupIDList[] = $group->id;

            $sourceProject = $this->$scm->apiGetSingleProject($MR->hostID, $MR->sourceProject);
            $isDeveloper   = $this->$scm->checkUserAccess($MR->hostID, 0, $sourceProject, $groupIDList, 'developer');

            if(!isset($gitUsers[$this->app->user->account]) || !$isDeveloper) return $this->sendError($this->lang->mr->errorLang[3], $this->createLink('mr', 'browse'));
        }

        $this->view->host = $host;
        $this->meZen->assignEditData($MR, $scm);
    }

    /**
     * 删除合并请求。
     * Delete a MR.
     *
     * @param  int    $MRID
     * @access public
     * @return void
     */
    public function delete(int $MRID)
    {
        $this->mr->deleteByID($MRID);

        if(dao::isError()) return $this->sendError(dao::getError());
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 合并请求详情。
     * View a MR.
     *
     * @param  int    $MRID
     * @access public
     * @return void
     */
    public function view(int $MRID)
    {
        $MR = $this->mr->getByID($MRID);
        if(!$MR) return $this->locate($this->createLink('mr', 'browse'));

        if(isset($MR->hostID)) $rawMR = $this->mr->apiGetSingleMR($MR->hostID, $MR->targetProject, $MR->mriid);
        if($MR->synced && (!isset($rawMR->id) || empty($rawMR))) return $this->display();

        /* Sync MR from GitLab to ZentaoPMS. */
        $MR   = $this->mr->apiSyncMR($MR);
        $host = $this->loadModel('pipeline')->getByID($MR->hostID);

        $projectOwner  = $projectEdit = false;
        $sourceProject = $this->loadModel($host->type)->apiGetSingleProject($MR->hostID, $MR->sourceProject);
        if(isset($MR->hostID) && !$this->app->user->admin)
        {
            $openID = $this->{$host->type}->getUserIDByZentaoAccount($MR->hostID, $this->app->user->account);
            if(!$projectOwner && isset($sourceProject->owner->id) && $sourceProject->owner->id == $openID) $projectOwner = true;
        }

        if($host->type == 'gitlab')
        {
            $groupIDList = array(0 => 0);
            $groups      = $this->gitlab->apiGetGroups($MR->hostID, 'name_asc', 'developer');
            foreach($groups as $group) $groupIDList[] = $group->id;

            $isDeveloper = $this->gitlab->checkUserAccess($MR->hostID, 0, $sourceProject, $groupIDList, 'developer');
            $gitUsers    = $this->gitlab->getUserAccountIdPairs($MR->hostID);
            if(isset($gitUsers[$this->app->user->account]) && $isDeveloper) $projectEdit = true;
        }
        elseif($host->type == 'gitea')
        {
            $projectEdit = (isset($sourceProject->allow_merge_commits) && $sourceProject->allow_merge_commits == true) ? true : false;
        }
        elseif($host->type == 'gogs')
        {
            $projectEdit = (isset($sourceProject->permissions->push) && $sourceProject->permissions->push) ? true : false;
        }

        $this->app->loadLang('productplan');
        $this->view->title         = $this->lang->mr->view;
        $this->view->MR            = $MR;
        $this->view->rawMR         = isset($rawMR) ? $rawMR : false;
        $this->view->actions       = $this->loadModel('action')->getList('mr', $MRID);
        $this->view->compile       = $this->loadModel('compile')->getById($MR->compileID);
        $this->view->compileJob    = $MR->jobID ? $this->loadModel('job')->getById($MR->jobID) : false;
        $this->view->projectOwner  = $projectOwner;
        $this->view->projectEdit   = $projectEdit;
        $this->view->sourceProject = $sourceProject;
        $this->view->targetProject = $this->{$host->type}->apiGetSingleProject($MR->hostID, $MR->targetProject);
        $this->view->sourceBranch  = $this->{$host->type}->apiGetSingleBranch($MR->hostID, $MR->sourceProject, $MR->sourceBranch);
        $this->view->targetBranch  = $this->{$host->type}->apiGetSingleBranch($MR->hostID, $MR->targetProject, $MR->targetBranch);
        $this->display();
    }

    /**
     * 定时任务，从GitLab API同步合并请求状态到禅道数据库，默认5分钟执行一次。
     * Crontab sync MR from GitLab API to Zentao database, default time 5 minutes to execute once.
     *
     * @access public
     * @return void
     */
    public function syncMR()
    {
        $MRList = $this->mr->getList();
        $this->mr->batchSyncMR($MRList);

        if(dao::isError())
        {
            echo json_encode(dao::getError());
            return true;
        }

        echo 'success';
    }

    /**
     * 通过合并请求。
     * Accept a MR.
     *
     * @param  int    $MRID
     * @access public
     * @return void
     */
    public function accept(int $MRID)
    {
        $MR = $this->mr->getByID($MRID);

        /* Judge that if this MR can be accepted. */
        if(isset($MR->needCI) and $MR->needCI == '1')
        {
            $compileStatus = empty($MR->compileID) ? 'fail' : $this->loadModel('compile')->getByID($MR->compileID)->status;

            if(isset($compileStatus) and $compileStatus != 'success')
            {
                return $this->send(array('result' => 'fail', 'message' => $this->lang->mr->needCI, 'locate' => helper::createLink('mr', 'view', "mr={$MRID}")));
            }
        }

        if(isset($MR->needApproved) and $MR->needApproved == '1')
        {
            if($MR->approvalStatus != 'approved')
            {
                return $this->send(array('result' => 'fail', 'message' => $this->lang->mr->needApproved, 'locate' => helper::createLink('mr', 'view', "mr={$MRID}")));
            }
        }

        if(isset($MR->hostID)) $rawMR = $this->mr->apiAcceptMR($MR);
        if(isset($rawMR->state) and $rawMR->state == 'merged')
        {
            $this->mr->logMergedAction($MR);
            return $this->send(array('result' => 'success', 'message' => $this->lang->mr->mergeSuccess, 'load' => true));
        }

        /* The type of variable `$rawMR->message` is string. This is different with apiCreateMR. */
        if(isset($rawMR->message))
        {
            $errorMessage = $this->mr->convertApiError($rawMR->message);
            return $this->sendError(sprintf($this->lang->mr->apiError->sudo, $errorMessage));
        }

        return $this->send(array('result' => 'fail', 'message' => $this->lang->mr->mergeFailed, 'locate' => helper::createLink('mr', 'view', "mr={$MRID}")));
    }

    /**
     * 获取合并请求的对比信息。
     * View diff between MR source and target branches.
     *
     * @param  int    $MRID
     * @access public
     * @return void
     */
    public function diff(int $MRID, string $encoding = '')
    {
        $encoding = empty($encoding) ? 'utf-8' : $encoding;
        $encoding = strtolower(str_replace('_', '-', $encoding)); /* Revert $config->requestFix in $encoding. */

        $MR = $this->mr->getByID($MRID);
        $this->view->title = $this->lang->mr->viewDiff;
        $this->view->MR    = $MR;
        if($MR->synced)
        {
            $rawMR = $this->mr->apiGetSingleMR($MR->hostID, $MR->targetProject, $MR->mriid);
            if(!isset($rawMR->id) || empty($rawMR)) return $this->display();
        }

        $diffs   = $this->mr->getDiffs($MR, $encoding);
        $arrange = $this->cookie->arrange ? $this->cookie->arrange : 'inline';
        if($this->server->request_method == 'POST')
        {
            if($this->post->arrange)
            {
                $arrange = $this->post->arrange;
                helper::setcookie('arrange', $arrange);
            }
            if($this->post->encoding) $encoding = $this->post->encoding;
        }

        $this->view->repo        = $this->loadModel('repo')->getByID($MR->repoID);
        $this->view->diffs       = $arrange == 'appose' ? $this->repo->getApposeDiff($diffs) : $diffs;
        $this->view->encoding    = $encoding;
        $this->view->oldRevision = $MR->targetBranch;
        $this->view->newRevision = $MR->sourceBranch;
        $this->display();
    }

    /**
     * 审核合并请求。
     * Approval for this MR.
     *
     * @param  int    $MRID
     * @param  string $action
     * @return void
     */
    public function approval(int $MRID, string $action = 'approve')
    {
        $MR = $this->mr->getByID($MRID);
        if($_POST)
        {
            $comment = $this->post->comment;
            $result  = $this->mr->approve($MR, $action, $comment);
            return $this->send($result);
        }

        $showCompileResult = false;
        if(!empty($MR->compileStatus))
        {
            $showCompileResult = true;
            $this->view->compileUrl = $this->createLink('job', 'view', "jobID={$MR->jobID}&compileID={$MR->compileID}");
        }

        $this->view->MR                = $MR;
        $this->view->action            = $action;
        $this->view->actions           = $this->loadModel('action')->getList('mr', $MRID);
        $this->view->users             = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->showCompileResult = $showCompileResult;
        $this->display();
    }

    /**
     * 关闭合并请求。
     * Close this MR.
     *
     * @param  int    $MRID
     * @access public
     * @return void
     */
    public function close(int $MRID)
    {
        $MR = $this->mr->getByID($MRID);
        $result = $this->mr->close($MR);

        return $this->send($result);
    }

    /**
     * 重新打开合并请求。
     * Reopen this MR.
     *
     * @param  int    $MRID
     * @access public
     * @return void
     */
    public function reopen(int $MRID)
    {
        $MR = $this->mr->getByID($MRID);
        return $this->send($this->mr->reopen($MR));
    }

    /**
     * link MR list.
     *
     * @param  int    $MRID
     * @param  string $type
     * @param  string $orderBy
     * @param  string $link
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @return void
     */
    public function link($MRID, $type = 'story', $orderBy = 'id_desc', $link = 'false', $param = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadLang('productplan');
        $this->app->loadLang('bug');
        $this->app->loadLang('task');
        $this->app->loadLang('release');

        $this->app->loadModuleConfig('release');
        $this->app->loadModuleConfig('task');
        $this->app->loadModuleConfig('repo');

        $MR       = $this->mr->getByID($MRID);
        $product  = $this->mr->getMRProduct($MR);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $storyPager = new pager(0, $recPerPage, $type == 'story' ? $pageID : 1);
        $bugPager   = new pager(0, $recPerPage, $type == 'bug' ? $pageID : 1);
        $taskPager  = new pager(0, $recPerPage, $type == 'task' ? $pageID : 1);

        $stories = $this->mr->getLinkList($MRID, $product->id, 'story', $type == 'story' ? $orderBy : '', $storyPager);
        $bugs    = $this->mr->getLinkList($MRID, $product->id, 'bug',   $type == 'bug'   ? $orderBy : '', $bugPager);
        $tasks   = $this->mr->getLinkList($MRID, $product->id, 'task',  $type == 'task'  ? $orderBy : '', $taskPager);
        $builds  = $this->loadModel('build')->getBuildPairs($product->id);

        $this->view->title        = $this->lang->mr->common . $this->lang->colon . $this->lang->mr->link;
        $this->view->MR           = $MR;
        $this->view->repoID       = $MR->repoID;
        $this->view->canBeChanged = true;
        $this->view->modulePairs  = $this->loadModel('tree')->getOptionMenu($product->id, 'story');
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->stories      = $stories;
        $this->view->bugs         = $bugs;
        $this->view->tasks        = $tasks;
        $this->view->product      = $product;
        $this->view->storyPager   = $storyPager;
        $this->view->bugPager     = $bugPager;
        $this->view->taskPager    = $taskPager;
        $this->view->type         = $type;
        $this->view->builds       = $builds;
        $this->view->orderBy      = $orderBy;
        $this->view->link         = $link;
        $this->view->param        = $param;
        $this->display();
    }

    /**
     * Link story to mr.
     *
     * @param int    $MRID
     * @param int    $productID
     * @param string $browseType
     * @param int    $param
     * @param string $orderBy
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @access public
     * @return void
     */
    public function linkStory($MRID, $productID = 0, $browseType = '', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        if(!empty($_POST['stories']))
        {
            $this->mr->link($MRID, $productID, 'story');

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $link = inlink('link', "MRID=$MRID&type=story&orderBy=$orderBy");
            return $this->send(array('result' => 'success', 'callback' => "loadTable('$link', 'storyTable')", 'closeModal' => true));
        }

        $this->loadModel('story');
        $this->app->loadLang('release');
        $this->app->loadLang('release');
        $this->app->loadModuleConfig('release');
        $this->app->loadLang('productplan');

        $product = $this->loadModel('product')->getById($productID);
        $modules = $this->loadModel('tree')->getOptionMenu($productID, 'story');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $storyStatusList = $this->lang->story->statusList;
        unset($storyStatusList['closed']);
        $queryID         = ($browseType == 'bySearch') ? (int) $param : 0;

        unset($this->config->product->search['fields']['product']);
        $this->config->product->search['actionURL']                   = $this->createLink('mr', 'linkStory', "MRID={$MRID}&productID={$productID}&browseType=bySearch&param=myQueryID&orderBy={$orderBy}");
        $this->config->product->search['queryID']                     = $queryID;
        $this->config->product->search['style']                       = 'simple';
        $this->config->product->search['params']['product']['values'] = array($product) + array('all' => $this->lang->product->allProductsOfProject);
        $this->config->product->search['params']['plan']['values']    = $this->loadModel('productplan')->getForProducts(array($productID => $productID));
        $this->config->product->search['params']['module']['values']  = $modules;
        $this->config->product->search['params']['status']            = array('operator' => '=', 'control' => 'select', 'values' => $storyStatusList);

        if($product->type == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->product->setMenu($productID, 0);
            $this->config->product->search['fields']['branch']           = $this->lang->product->branch;
            $this->config->product->search['params']['branch']['values'] = $this->loadModel('branch')->getPairs($productID, 'noempty');
        }
        $this->loadModel('search')->setSearchParams($this->config->product->search);

        $MR             = $this->mr->getByID($MRID);
        $relatedStories = $this->mr->getCommitedLink($MR, 'story');

        $linkedStories = $this->mr->getLinkList($MRID, $product->id, 'story');
        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($productID, 0, $queryID, $orderBy, '', 'story', array_keys($linkedStories), '', $pager);
        }
        else
        {
            $allStories = $this->story->getProductStories($productID, 0, '0', 'draft,reviewing,active,changing', 'story', $orderBy, false, array_keys($linkedStories), $pager);
        }

        $this->view->modules        = $modules;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->allStories     = $allStories;
        $this->view->relatedStories = $relatedStories;
        $this->view->product        = $product;
        $this->view->MRID           = $MRID;
        $this->view->browseType     = $browseType;
        $this->view->param          = $param;
        $this->view->orderBy        = $orderBy;
        $this->view->pager          = $pager;
        $this->display();
    }

    /**
     * Link bug to mr.
     *
     * @param int    $MRID
     * @param int    $productID
     * @param string $browseType
     * @param int    $param
     * @param string $orderBy
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @access public
     * @return void
     */
    public function linkBug($MRID, $productID = 0, $browseType = '', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        if(!empty($_POST['bugs']))
        {
            $this->mr->link($MRID, $productID, 'bug');

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $link = inlink('link', "MRID=$MRID&type=bug&orderBy=$orderBy");
            return $this->send(array('result' => 'success', 'callback' => "loadTable('$link', 'bugTable')", 'closeModal' => true));
        }

        $this->loadModel('bug');
        $this->app->loadLang('release');
        $this->app->loadModuleConfig('release');
        $this->app->loadLang('productplan');
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;

        $product = $this->loadModel('product')->getById($productID);
        $modules = $this->loadModel('tree')->getOptionMenu($productID, 'bug');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->config->bug->search['actionURL']                         = $this->createLink('mr', 'linkBug', "MRID={$MRID}&productID={$productID}&browseType=bySearch&param=myQueryID&orderBy={$orderBy}");
        $this->config->bug->search['queryID']                           = $queryID;
        $this->config->bug->search['style']                             = 'simple';
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getForProducts(array($productID => $productID));
        $this->config->bug->search['params']['module']['values']        = $modules;
        $this->config->bug->search['params']['execution']['values']     = $this->product->getExecutionPairsByProduct($productID);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs($productID, 'all', 'releasetag');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];

        unset($this->config->bug->search['fields']['product']);
        if($product->type == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->product->setMenu($productID, 0);
            $this->config->bug->search['fields']['branch']           = $this->lang->product->branch;
            $this->config->bug->search['params']['branch']['values'] = $this->loadModel('branch')->getPairs($productID, 'noempty');
        }
        $this->loadModel('search')->setSearchParams($this->config->bug->search);

        $MR          = $this->mr->getByID($MRID);
        $relatedBugs = $this->mr->getCommitedLink($MR, 'bug');

        $linkedBugs = $this->mr->getLinkList($MRID, $product->id, 'bug');
        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch($productID, 0, $queryID, $orderBy, array_keys($linkedBugs), $pager);
        }
        else
        {
            $allBugs = $this->bug->getActiveBugs($productID, 0, '0', array_keys($linkedBugs), $pager, $orderBy);
        }

        $this->view->modules     = $modules;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->allBugs     = $allBugs;
        $this->view->relatedBugs = $relatedBugs;
        $this->view->product     = $product;
        $this->view->MRID        = $MRID;
        $this->view->browseType  = $browseType;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->display();
    }

    /**
     * Link task to mr.
     *
     * @param int    $MRID
     * @param int    $productID
     * @param string $browseType
     * @param int    $param
     * @param string $orderBy
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @access public
     * @return void
     */
    public function linkTask($MRID, $productID = 0, $browseType = 'unclosed', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        if(!empty($_POST['tasks']))
        {
            $this->mr->link($MRID, $productID, 'task');

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $link = inlink('link', "MRID=$MRID&type=task&orderBy=$orderBy");
            return $this->send(array('result' => 'success', 'callback' => "loadTable('$link', 'taskTable')", 'closeModal' => true));
        }

        $this->loadModel('execution');
        $this->loadModel('product');
        $this->app->loadLang('task');
        $this->app->loadModuleConfig('repo');

        /* Set browse type. */
        $browseType = strtolower($browseType);
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;

        $product = $this->loadModel('product')->getById($productID);
        $modules = $this->loadModel('tree')->getOptionMenu($productID, 'task');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->config->execution->search['actionURL']                     = $this->createLink('mr', 'linkTask', "MRID={$MRID}&productID={$productID}&browseType=bySearch&param=myQueryID&orderBy={$orderBy}");
        $this->config->execution->search['queryID']                       = $queryID;
        $this->config->execution->search['params']['module']['values']    = $modules;
        $this->config->execution->search['params']['execution']['values'] = $this->product->getExecutionPairsByProduct($productID);
        $this->loadModel('search')->setSearchParams($this->config->execution->search);

        $MR           = $this->mr->getByID($MRID);
        $relatedTasks = $this->mr->getCommitedLink($MR, 'task');
        $linkedTasks  = $this->mr->getLinkList($MRID, $product->id, 'task');

        /* Get executions by product. */
        $productExecutions   = $this->product->getExecutionPairsByProduct($productID);
        $productExecutionIDs = array_filter(array_keys($productExecutions));
        $this->config->execution->search['params']['execution']['values'] = array_filter($productExecutions);

        /* Get tasks by executions. */
        $allTasks = array();
        foreach($productExecutionIDs as $productExecutionID)
        {
            $tasks    = $this->execution->getTasks(0, $productExecutionID, array(), $browseType, $queryID, 0, $orderBy, null);
            $allTasks = array_merge($tasks, $allTasks);
        }
        /* Filter linked tasks. */
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
        $this->view->relatedTasks = $relatedTasks;
        $this->view->product      = $product;
        $this->view->MRID         = $MRID;
        $this->view->browseType   = $browseType;
        $this->view->param        = $param;
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->display();
    }

    /**
     * UnLink an mr link.
     *
     * @param int    $MRID
     * @param int    $productID
     * @param string $type
     * @param int    $linkID
     * @param string $confirm
     * @access public
     * @return mix
     */
    public function unlink($MRID, $productID, $type, $linkID)
    {
        $this->app->loadLang('productplan');

        $this->mr->unlink($MRID, $productID, $type, $linkID);

        /* if ajax request, send result. */
        if($this->server->ajax)
        {
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
            }
            else
            {
                $link = inlink('link', "MRID=$MRID&type=$type");
                $response['result']  = 'success';
                $response['message'] = '';
                $response['load']    = $link;
            }
            return $this->send($response);
        }
        return print(js::reload('parent'));
    }

    /**
     * Add a review for this review.
     *
     * @param  int    $repoID
     * @param  int    $mr
     * @param  int    $v1
     * @param  int    $v2
     * @access public
     * @return void
     */
    public function addReview($repoID, $mr, $v1, $v2)
    {
        /* Handle the exception that when $repoID is empty. */
        if($repoID == "0") $this->send(array());

        $this->loadModel('repo');
        if(!empty($_POST))
        {
            $v1 = helper::safe64Decode($v1);
            $v2 = helper::safe64Decode($v2);
            if($this->post->reviewType == 'bug')  $result = $this->mr->saveBug($repoID, $mr, $v1, $v2);
            if($this->post->reviewType == 'task') $result = $this->mr->saveTask($repoID, $mr, $v1, $v2);
            if($result['result'] == 'fail') return print(json_encode($result));

            $objectID = $result['id'];
            $repo     = $this->repo->getByID($repoID);
            /* Handle the exception that when $repo is empty. */
            if(empty($repo) or empty($result)) $this->send(json_encode(array()));

            $location = sprintf($this->lang->repo->reviewLocation, $this->post->entry ? base64_decode($this->post->entry) : '', $repo->SCM != 'Subversion' ? substr($v2, 0, 10) : $v2, $this->post->begin, $this->post->end);
            $link     = $this->createLink('mr', 'diff', "mr=$mr") . '#L' . $this->post->begin;

            $actionID = $this->loadModel('action')->create($this->post->reviewType, $objectID, 'repoCreated', '', html::a($link, $location));
            $this->loadModel('mail')->sendmail($objectID, $actionID);

            echo json_encode($result);
        }
    }

    /**
     * AJAX: Get MR target projects.
     *
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  string $scm
     * @access public
     * @return void
     */
    public function ajaxGetMRTargetProjects($hostID, $projectID, $scm = 'gitlab')
    {
        $this->loadModel($scm);

        if($scm != 'gitlab') $projectID = urldecode(base64_decode($projectID));
        /* First step: get forks. Only get first level forks(not recursively). */
        $projects = $scm == 'gitlab' ? $this->$scm->apiGetForks($hostID, $projectID) : array();

        /* Second step: get project itself. */
        $projects[] = $this->$scm->apiGetSingleProject($hostID, $projectID, true);

        /* Last step: find its upstream recursively. */
        $project = $this->$scm->apiGetUpstream($hostID, $projectID);
        if(!empty($project)) $projects[] = $project;

        if(!empty($project) and isset($project->id))
        {
            $project = $this->$scm->apiGetUpstream($hostID, $project->id);
            if(!empty($project)) $projects[] = $project;
        }

        if($scm == 'gitlab')
        {
            $groupIDList = array(0 => 0);
            $groups      = $this->$scm->apiGetGroups($hostID, 'name_asc', 'developer');
            foreach($groups as $group) $groupIDList[] = $group->id;
            foreach($projects as $key => $project)
            {
                if(!$this->$scm->checkUserAccess($hostID, 0, $project, $groupIDList, 'developer')) unset($projects[$key]);
            }

            if(!$projects) return $this->send(array('message' => array()));
        }

        $options = "<option value=''></option>";
        foreach($projects as $project)
        {
            if($scm == 'gitlab')
            {
                $options .= "<option value='{$project->id}' data-name='{$project->name}'>{$project->name_with_namespace}</option>";
            }
            else
            {
                $options .= "<option value='{$project->full_name}' data-name='{$project->full_name}'>{$project->full_name}</option>";
            }
        }

        $this->send($options);
    }

    /**
     * AJAX: Get repo list.
     *
     * @param  int $hostID
     * @param  int $projectID
     * @return void
     */
    public function ajaxGetRepoList($hostID, $projectID)
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if($host->type != 'gitlab') $projectID = urldecode(base64_decode($projectID));

        $repoList = $this->loadModel('repo')->getRepoListByClient($hostID, $projectID);

        if(!$repoList) return $this->send(array('message' => array()));
        $options = "<option value=''></option>";
        foreach($repoList as $repo) $options .= "<option value='{$repo->id}' data-name='{$repo->name}'>[{$repo->id}] {$repo->name}</option>";
        $this->send($options);
    }

    /**
     * AJAX: Get job list.
     *
     * @param  int $repoID
     * @return void
     */
    public function ajaxGetJobList($repoID)
    {
        $this->loadModel('job');
        $jobList = $this->job->getListByRepoID($repoID);

        if(!$jobList) return $this->send(array('message' => array()));
        $options = "<option value=''></option>";
        foreach($jobList as $job) $options .= "<option value='{$job->id}' data-name='{$job->name}'>[{$job->id}] {$job->name}</option>";
        $this->send($options);
   }

   /**
    * AJAX: Get compile list.
    *
    * @param  int $jobID
    * @return void
    */
   public function ajaxGetCompileList($jobID)
   {
        $this->loadModel('compile');
        $compileList = $this->compile->getListByJobID($jobID);

        if(!$compileList) return $this->send(array('message' => array()));
        $options = "<option value=''></option>";
        foreach($compileList as $compile) $options .= "<option value='{$compile->id}' data-name='{$compile->name}'>[{$compile->id}] [{$this->lang->compile->statusList[$compile->status]}] {$compile->name}</option>";
        $this->send($options);
   }

   /**
    * Ajax check same opened mr for source branch.
    *
    * @param  int    $hostID
    * @access public
    * @return void
    */
   public function ajaxCheckSameOpened($hostID)
   {
       $sourceProject = $this->post->sourceProject;
       $sourceBranch  = $this->post->sourceBranch;
       $targetProject = $this->post->targetProject;
       $targetBranch  = $this->post->targetBranch;

       $result = $this->mr->checkSameOpened($hostID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
       return print(json_encode($result));
   }

   /**
    * Ajax get branch pivs.
    *
    * @param  int        $hostID
    * @param  int|string $project
    * @access public
    * @return void
    */
   public function ajaxGetBranchPivs($hostID, $project)
   {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        $scm  = $host->type;
        if(in_array($scm, array('gitea', 'gogs'))) $project = urldecode(base64_decode($project));

        $branchPrivs = array();
        $branches    = $this->loadModel($scm)->apiGetBranchPrivs($hostID, $project);
        foreach($branches as $branch) $branchPrivs[$branch->name] = $branch->name;
        return print(json_encode($branchPrivs));
   }
}
