<?php
declare(strict_types=1);
/**
 * The control file of ppm module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     ppm
 * @link        https://www.zentao.net
 * @property    ppmModel $ppm
 * @property    ppmZen   $ppmZen
 */
class ppm extends control
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

            $this->view->objectID = 0;
            if(in_array($this->app->tab, array('execution', 'project'))) $this->view->objectID = $this->session->{$this->app->tab};

            if($this->app->tab == 'execution')
            {
                $this->view->executionID = $this->session->execution;
                $this->loadModel('execution')->setMenu((int)$this->session->execution);
            }

            if($this->app->tab == 'project')
            {
                $this->loadModel('project')->setMenu((int)$this->session->project);
                $this->view->projectID = $this->session->project;
            }
        }
    }

    /**
     * 获取合并请求列表.
     * Browse ppm.
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
        $serverHeath = $this->loadModel('gitfox')->checkHealth();
        if(!$serverHeath) return $this->locate($this->createLink('gitfox', "installGitFox", 'inDevOps=1'));

        $this->loadModel('repo');
        if($this->app->tab == 'execution')
        {
            $repos = $this->repo->getRepoPairs('execution', $objectID);
            if(empty($repos)) return $this->locate($this->createLink('execution', 'task', "objectID={$objectID}"));

            $this->session->set('execution', $objectID);
            $execution = $this->loadModel('execution')->getByID($objectID);
            if($execution && $execution->type === 'kanban') return $this->locate($this->createLink('execution', 'kanban', "executionID=$objectID"));

            $features = $this->execution->getExecutionFeatures($execution);
            if(!$features['devops']) return $this->locate($this->createLink('execution', 'task', "objectID=$objectID"));

            $this->loadModel('execution')->setMenu($objectID);
            $this->view->executionID = $objectID;
        }
        elseif($this->app->tab == 'project')
        {
            $repos = $this->repo->getRepoPairs('execution', $objectID);
            if(empty($repos)) return $this->locate($this->createLink('project', 'index', "projectID=$objectID"));
            $this->session->set('project', $objectID);
            $this->loadModel('project')->setMenu($objectID);
            $this->view->projectID = $objectID;
        }

        if(in_array($this->app->tab, array('execution', 'project')) && $objectID) return print($this->fetch('ppm', 'browseByExecution', "repoID={$repoID}&mode={$mode}&param={$param}&objectID={$objectID}&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}"));

        $repoList = $this->repo->getListByPriv();
        if(empty($repoList)) $this->locate($this->repo->createLink('create'));

        if(!$repoID) $repoID = key($repoList);
        $repoID = $this->repo->saveState($repoID, $objectID);
        if(!isset($repoList[$repoID])) return $this->locate($this->createLink('repo', 'browse', "repoID=$repoID&objectID=$objectID"));

        $repo = $repoList[$repoID];
        $this->loadModel('ci')->setMenu($repo->id);

        if($param == 'assignee' || $param == 'creator')
        {
            $mode  = $param;
            $param = $this->app->user->account;
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $ppmList       = $this->ppm->getList($mode, $param, $orderBy, array(), $repoID, 0, $pager);
        $canEdit       = common::hasPriv($this->app->rawModule, 'edit');
        $reviewResults = $this->ppm->getReviewResults(array_keys($ppmList), $repoID);
        foreach($ppmList as $ppm)
        {
            $ppm->canEdit        = $canEdit ? '' : 'disabled';
            $ppm->approvalStatus = !empty($reviewResults[$ppm->id]) && $reviewResults[$ppm->id]['result'] ? $this->lang->ppm->approve : $this->lang->ppm->reject;

            if($ppm->status == 'merged' || $ppm->status == 'closed') $ppm->mergeStatus = $ppm->status;
        }

        $this->view->title    = $this->lang->ppm->common . $this->lang->hyphen . $this->lang->ppm->browse;
        $this->view->ppmList  = $ppmList;
        $this->view->pager    = $pager;
        $this->view->mode     = $mode;
        $this->view->param    = $param;
        $this->view->objectID = $objectID;
        $this->view->repo     = $repo;
        $this->view->orderBy  = $orderBy;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 获取执行下合并请求列表.
     * Browse ppm for execution.
     *
     * @param  int    $repoID
     * @param  string $mode
     * @param  string $param
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseByExecution(int $repoID = 0, string $mode = 'status', string $param = 'opened', int $projectID = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        if($param == 'assignee' || $param == 'creator')
        {
            $mode  = $param;
            $param = $this->app->user->account;
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $ppmList  = $this->ppm->getList($mode, $param, $orderBy, array(), $repoID, $projectID, $pager);
        $repoList = $this->loadModel('repo')->getList($projectID);

        $repoPairs = array();
        foreach($repoList as $repo)
        {
            $repoPairs[$repo->id] = $repo->name;
        }

        $objectName = $this->app->tab == 'project' ? 'projectID' : 'executionID';
        $this->view->{$objectName} = $projectID;

        $this->view->title     = $this->lang->ppm->common . $this->lang->hyphen . $this->lang->ppm->browse;
        $this->view->ppmList   = $ppmList;
        $this->view->pager     = $pager;
        $this->view->mode      = $mode;
        $this->view->repoID    = $repoID;
        $this->view->param     = $param;
        $this->view->objectID  = $projectID;
        $this->view->repoList  = $repoList;
        $this->view->repoPairs = $repoPairs;
        $this->view->orderBy   = $orderBy;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 创建合并请求.
     * Create MR function.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $type
     * @access public
     * @return void
     */
    public function create(int $repoID = 0, int $objectID = 0, string $sourceBranch = '', string $targetBranch = '')
    {
        $sourceBranch = helper::safe64Decode($sourceBranch);
        $targetBranch = helper::safe64Decode($targetBranch);

        $repoID = $this->loadModel('repo')->saveState($repoID);
        if($repoID) $this->ci->setMenu($repoID);
        $repo   = $this->repo->getByID($repoID);
        $scm    = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $branches        = $scm->branch('all', 'date_desc');
        $branchNameList  = array_column($branches, 'name', 'name');
        $defaultBranches = array_values(array_slice($branchNameList, 0, 2));
        $targetBranch    = $targetBranch ?: zget($defaultBranches, 0, '');
        $sourceBranch    = $sourceBranch ?: zget($defaultBranches, 1, '');
        if($targetBranch && !$sourceBranch) $sourceBranch = $targetBranch;
        $flow            = $this->loadModel('reporeviewflow')->getByBranchName($repoID, $targetBranch);
        if(!empty($flow))
        {
            $flow->definition = json_decode($flow->definition);
            $flow->reviewers  = arrayUnion(array_filter($flow->definition->reviewFlow->approvals->defaultReviewers), array_filter($flow->definition->reviewFlow->approvals->specifiedReviewers));
        }
        $mergeCheckMessage = $this->loadModel('gitfox')->apiGetMergeCheckMessage($repoID, $sourceBranch, $targetBranch);

        if($_POST)
        {
            $approvalflow = !empty($flow) && !empty($flow->definition->reviewFlow->approvals->approvalID) ? $flow->definition->reviewFlow->approvals->approvalID : 0;
            $ppm = form::data($this->config->ppm->form->create)
                ->add('createdBy', $this->app->user->account)
                ->add('repoID', $repoID)
                ->add('sourceRepoID', $repoID)
                ->add('targetRepoID', $repoID)
                ->add('status', 'opened')
                ->add('reviewFlowID', !empty($flow->id) ? $flow->id : 0)
                ->add('sourceSHA', zget($mergeCheckMessage, 'sourceSHA', ''))
                ->add('mergeTargetSHA', zget($mergeCheckMessage, 'targetSHA', ''))
                ->add('approvalflow', $approvalflow)
                ->add('executionID', $objectID)
                ->skipSpecial('title,description')
                ->get();

            $this->ppm->create($ppm);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $linkParams = $this->app->tab == 'execution' || $this->app->tab == 'project' ? "repoID=0&mode=status&param=opened&objectID={$objectID}" : "repoID={$repoID}";

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => helper::createLink($this->moduleName, 'browse', $linkParams)));
        }

        $mergeRuleResult   = $this->ppm->checkMergeRule($repoID, $sourceBranch, $targetBranch);
        $checkSourceBranch = $mergeRuleResult[$sourceBranch]['result'];
        $checkTargetBranch = $mergeRuleResult[$targetBranch]['result'];

        if($mergeCheckMessage)
        {
            $canMerge      = !empty($mergeCheckMessage->mergeable) && $checkSourceBranch && $checkTargetBranch;
            $conflictFiles = zget($mergeCheckMessage, 'conflictFiles', array());
        }
        $message    = $this->ppmZen->parseCreateCheckMsg($mergeCheckMessage, $mergeRuleResult, $sourceBranch, $targetBranch);
        $branchRule = $this->loadModel('repobranchrule')->getRuleByBranchName($repoID, $targetBranch);
        if(!empty($branchRule) && !empty($branchRule->ppmCreateUser) && !in_array($this->app->user->account, explode(',', $branchRule->ppmCreateUser)))
        {
            $ppmCreateUsers = explode(',', $branchRule->ppmCreateUser);
            $ppmCreateUsers = $this->loadModel('user')->getListByAccounts($ppmCreateUsers);

            $message  = sprintf($this->lang->ppm->notice->userNotAllowCreate, implode(',', array_column($ppmCreateUsers, 'realname')));
            $canMerge = false;
        }
        if($sourceBranch == $targetBranch) $message = $this->lang->ppm->notice->sameBranch;
        if(in_array($this->app->tab, array('execution', 'project')) && $objectID)
        {
            $repoList = $this->loadModel('repo')->getList($objectID);
            foreach($repoList as $repoInfo)
            {
                if(empty($repoInfo->mirror)) $repoPairs[$repoInfo->id] = $repoInfo->name;
            }
        }

        $this->view->title             = $this->lang->ppm->create;
        $this->view->users             = $this->repo->getRepoMembers($repo);
        $this->view->repo              = $repo;
        $this->view->repoPairs         = empty($repoPairs) ? array() : $repoPairs;
        $this->view->repoID            = $repoID;
        $this->view->executionID       = $objectID;
        $this->view->objectID          = $objectID;
        $this->view->branches          = $branchNameList;
        $this->view->defaultBranch     = $targetBranch;
        $this->view->activeBranch      = $sourceBranch;
        $this->view->reviewers         = implode(',', zget($flow, 'reviewers', array()));
        $this->view->flow              = $flow;
        $this->view->mergeMessage      = isset($message)       ? $message       : '';
        $this->view->canMerge          = isset($canMerge)      ? $canMerge      : true;
        $this->view->conflictFiles     = isset($conflictFiles) ? $conflictFiles : array();
        $this->view->checkSourceBranch = $checkSourceBranch;
        $this->view->checkTargetBranch = $checkTargetBranch;
        $this->view->commitMessage     = empty($branches[$sourceBranch]) ? '' : $branches[$sourceBranch]->commit->message;
        $this->display();
    }

    /**
     * 编辑合并请求。
     * Edit MR function.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit(int $id)
    {
        if($_POST)
        {
            $ppm = form::data($this->config->ppm->form->edit)
                ->add('editedBy', $this->app->user->account)
                ->skipSpecial('title')
                ->get();
            $result = $this->ppm->update($id, $ppm);
            return $this->send($result);
        }

        $ppm      = $this->ppm->fetchByID($id);
        $repoID = $this->loadModel('repo')->saveState($ppm->repoID);
        if($repoID) $this->ci->setMenu($repoID);
        $flow     = $this->loadModel('reporeviewflow')->getById($ppm->reviewFlowID);
        $reviewID = !empty($flow) && !empty($flow->definition->reviewFlow) ? $flow->definition->reviewFlow->approvals->approvalID : 0;

        $this->view->title     = $this->lang->ppm->edit;
        $this->view->ppm       = $ppm;
        $this->view->repo      = $this->loadModel('repo')->getByID($ppm->repoID);
        $this->view->repoID    = $ppm->repoID;
        $this->view->reviewers = !empty($reviewID) ? array() : array_keys($this->ppm->getReviewers($id));
        $this->view->users     = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->display();
    }

    /**
     * 删除合并请求。
     * Delete a PPM.
     *
     * @param  int    $MRID
     * @access public
     * @return void
     */
    public function delete(int $id)
    {
        $ppm = $this->ppm->fetchByID($id);
        $this->ppm->deleteByID($id);

        if(dao::isError()) return $this->sendError(dao::getError());
        return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'browse', "repoID={$ppm->repoID}")));
    }

    /**
     * 合并请求详情。
     * View a MR.
     *
     * @param  int    $id
     * @param  string $type
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view(int $id, string $type = 'basic', string $param = 'all', int $recTotal = 0, int $recPerPage = 20, int $pageID = 0)
    {
        $ppm  = $this->ppm->fetchByID($id);
        $repo = $this->loadModel('repo')->getByID($ppm->repoID);
        $flow = $this->loadModel('reporeviewflow')->getByID(zget($ppm, 'reviewFlowID', 0));
        $scm  = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $this->app->loadClass('pager', true);
        $commitPager = new pager($type == 'commit' ? $recTotal : 0, $recPerPage, $type == 'commit' ? $pageID : 1);
        $bugPager    = new pager($type == 'bug'    ? $recTotal : 0, $recPerPage, $type == 'bug'    ? $pageID : 1);
        $objectPager = new pager($type == 'object' ? $recTotal : 0, $recPerPage, $type == 'object' ? $pageID : 1);

        $encoding = 'utf-8';
        if($type == 'files')
        {
            $encoding = empty($param) ? 'utf-8' : $param;
            $encoding = strtolower(str_replace('_', '-', $encoding)); /* Revert $config->requestFix in $encoding. */
        }
        $diffs   = $scm->diff('', $ppm->mergeBaseSHA, $ppm->sourceSHA, 'yes', 'isBranchOrTag', true);
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
        $reviewID         = !empty($flow) && !empty($flow->definition->reviewFlow) ? $flow->definition->reviewFlow->approvals->approvalID : 0;
        $reviewers        = !empty($reviewID) ? array() : $this->ppm->getReviewers($id);
        $reviewResult     = $this->ppm->getReviewResult($reviewers, empty($flow) ? array() : $flow);
        $defaultMergeType = $this->cookie->mergeType ? $this->cookie->mergeType : 'rebase';

        $this->view->title            = $this->lang->ppm->view;
        $this->view->ppm              = $ppm;
        $this->view->reviewers        = $reviewers;
        $this->view->reviewResult     = $reviewResult;
        $this->view->repo             = $repo;
        $this->view->repoID           = $repo->id;
        $this->view->flow             = $flow;
        $this->view->commitLogs       = $this->ppm->apiGetMRCommits($ppm->targetRepoID, $ppm->id, $commitPager);
        $this->view->bugs             = $this->ppm->getBugsByCommits($repo->id, $ppm->id, $bugPager);
        $this->view->linkObjects      = $this->ppm->getRelationByBranch($repo, $ppm->sourceSHA, $ppm->mergeBaseSHA, $param, $objectPager);
        $this->view->commitPager      = $commitPager;
        $this->view->bugPager         = $bugPager;
        $this->view->objectPager      = $objectPager;
        $this->view->type             = $type;
        $this->view->encoding         = $encoding;
        $this->view->diffs            = $arrange == 'appose' ? $this->repo->getApposeDiff($diffs) : $diffs;
        $this->view->users            = $this->loadModel('user')->getPairs('noletter');
        $this->view->oldRevision      = $ppm->targetBranch;
        $this->view->newRevision      = $ppm->sourceBranch;
        $this->view->defaultMergeType = $defaultMergeType;
        $this->view->checkResult      = $this->ppmZen-> getCheckResult($ppm, $reviewResult, $this->view->bugs, $defaultMergeType);
        $this->view->param            = $param;
        $this->view->rule             = $this->loadModel('repobranchrule')->getRuleByBranchName($ppm->targetRepoID, $ppm->targetBranch);
        $this->view->pipelines        = $this->ppm->getPipelinesByPPM($ppm);
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
        $ppmList = $this->ppm->getList();
        $this->ppm->batchSyncMR($ppmList);

        if(dao::isError())
        {
            echo json_encode(dao::getError());
            return true;
        }

        echo 'success';
    }

    /**
     * 审核合并请求。
     * review for this MR.
     *
     * @param  int    $id
     * @param  string $action
     * @return void
     */
    public function review(int $id)
    {
        $ppm       = $this->ppm->fetchByID($id);
        $reviewers = $this->ppm->getReviewers($id);
        if($_POST)
        {
            $data = form::data($this->config->ppm->form->review)->get();
            if($data->decision == 'reject' && empty($data->opinion))
            {
                return $this->sendError(array('opinion' => sprintf($this->lang->error->notempty, $this->lang->ppm->opinion)));
            }

            $this->ppm->review($id, $data);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create($this->moduleName, $id, $data->decision == 'approved' ? 'approve' : 'reject');
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title    = $this->lang->ppm->review;
        $this->view->reviewer = zget($reviewers, $this->app->user->account, array());
        $this->view->ppm      = $ppm;
        $this->display();
    }

    /**
     * 关闭合并请求。
     * Close this ppm.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function close(int $id)
    {
        $this->ppm->close($id);
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->loadModel('action')->create($this->moduleName, $id, 'closed');
        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 重新打开合并请求。
     * Reopen this MR.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function reopen(int $id)
    {
        $ppm  = $this->ppm->fetchByID($id);
        $repo = $this->loadModel('repo')->getByID($ppm->repoID);
        $scm  = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $branches = $scm->branch();
        if(!$ppm->flow && !in_array($ppm->targetBranch, $branches)) return $this->sendError($this->lang->ppm->targetBranchNotExist);
        if(!$ppm->flow && !in_array($ppm->sourceBranch, $branches)) return $this->sendError($this->lang->ppm->sourceBranchNotExist);

        $checkSameOpened = $this->ppm->checkSameOpened($ppm->repoID, $ppm->sourceRepoID, $ppm->sourceBranch, $ppm->targetRepoID, $ppm->targetBranch);
        if($checkSameOpened['result'] == 'fail') return $this->sendError($checkSameOpened['message']);

        $this->ppm->reopen($id);
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->loadModel('action')->create($this->moduleName, $id, 'reopen');
        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 获取合并请求的关联信息。
     * link MR list.
     *
     * @param  int    $id
     * @param  string $type
     * @param  string $orderBy
     * @param  string $param
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @return void
     */
    public function link(int $id, string $type = 'story', string $orderBy = 'id_desc', string $param = '', int $recPerPage = 20, int $pageID = 1)
    {
        $ppm     = $this->ppm->fetchByID($id);
        $product = $this->ppm->getMRProduct($ppm);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $storyPager = new pager(0, $recPerPage, $type == 'story' ? $pageID : 1);
        $bugPager   = new pager(0, $recPerPage, $type == 'bug' ? $pageID : 1);
        $taskPager  = new pager(0, $recPerPage, $type == 'task' ? $pageID : 1);

        $productID = $product ? $product->id : 0;
        $stories = $this->ppm->getLinkList($id, 'story', $type == 'story' ? $orderBy : '', $storyPager);
        $bugs    = $this->ppm->getLinkList($id, 'bug',   $type == 'bug'   ? $orderBy : '', $bugPager);
        $tasks   = $this->ppm->getLinkList($id, 'task',  $type == 'task'  ? $orderBy : '', $taskPager);
        $builds  = $this->loadModel('build')->getBuildPairs($productID);

        $this->view->title      = $this->lang->ppm->common . $this->lang->hyphen . $this->lang->ppm->link;
        $this->view->ppm        = $ppm;
        $this->view->repoID     = $ppm->repoID;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->stories    = $stories;
        $this->view->bugs       = $bugs;
        $this->view->tasks      = $tasks;
        $this->view->product    = $product;
        $this->view->storyPager = $storyPager;
        $this->view->bugPager   = $bugPager;
        $this->view->taskPager  = $taskPager;
        $this->view->type       = $type;
        $this->view->builds     = $builds;
        $this->view->orderBy    = $orderBy;
        $this->view->param      = $param;
        $this->display();
    }

    /**
     * 获取合并请求可关联的需求列表。
     * Link story to mr.
     *
     * @param  int    $MRID
     * @param  int    $repoID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory(int $id, int $repoID = 0, string $browseType = '', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 10, int $pageID = 1)
    {
        if(!empty($_POST['stories']))
        {
            $this->ppm->link($id, 'story', $this->post->stories);
            if(dao::isError()) return $this->sendError(dao::getError());

            $link = $this->createLink($this->app->rawModule,'link', "id=$id&type=story&orderBy=$orderBy");
            return $this->sendSuccess(array('load' => $link, 'closeModal' => true));
        }

        $this->loadModel('story');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $queryID = ($browseType == 'bySearch') ? (int) $param : 0;
        $this->ppmZen->buildLinkStorySearchForm($id, $repoID, $orderBy, $queryID);

        $repo          = $this->loadModel('repo')->fetchByID($repoID);
        $productID     = $repo ? $repo->product : 0;
        $linkedStories = $this->ppm->getLinkList($id, 'story');
        if($browseType == 'bySearch')
        {
            $this->session->set('repoID', $repoID);
            $allStories = $this->story->getBySearch('all', 0, $queryID, $orderBy, 0, 'story', array_keys($linkedStories), '', $pager);
        }
        else
        {
            $allStories = $this->story->getProductStories($productID, 'all', '0', 'draft,reviewing,active,changing', 'story', $orderBy, true, array_keys($linkedStories), $pager);
        }

        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->allStories = $allStories;
        $this->view->id         = $id;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->repoID     = $repoID;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * 获取合并请求可关联的Bug列表。
     * Link bug to mr.
     *
     * @param  int    $id
     * @param  int    $repoID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBug(int $id, int $repoID = 0, string $browseType = '', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 10, int $pageID = 1)
    {
        if(!empty($_POST['bugs']))
        {
            $this->ppm->link($id, 'bug', $this->post->bugs);

            if(dao::isError()) return $this->sendError(dao::getError());

            $link = $this->createLink($this->app->rawModule,'link', "id=$id&type=bug&orderBy=$orderBy");
            return $this->sendSuccess(array('load' => $link, 'closeModal' => true));
        }

        $this->loadModel('bug');
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        $this->ppmZen->buildLinkBugSearchForm($id, $repoID, $orderBy, $queryID);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $repo          = $this->loadModel('repo')->fetchByID($repoID);
        $productIdList = $repo ? explode(',', trim($repo->product, ',')) : 0;

        $linkedBugs = $this->ppm->getLinkList($id, 'bug');
        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch('bug', $productIdList, 0, 0, 0, $queryID, implode(',', array_keys($linkedBugs)), $orderBy, $pager);
        }
        else
        {
            $allBugs = $this->bug->getActiveBugs($productIdList, 0, '0', array_keys($linkedBugs), $pager, $orderBy);
        }

        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->allBugs    = $allBugs;
        $this->view->id         = $id;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->repoID     = $repoID;
        $this->display();
    }

    /**
     * 获取合并请求可关联的任务列表。
     * Link task to mr.
     *
     * @param int    $id
     * @param int    $repoID
     * @param string $browseType
     * @param int    $param
     * @param string $orderBy
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @access public
     * @return void
     */
    public function linkTask(int $id, int $repoID = 0, string $browseType = 'unclosed', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 10, int $pageID = 1)
    {
        if(!empty($_POST['tasks']))
        {
            $this->ppm->link($id, 'task', $this->post->tasks);
            if(dao::isError()) return $this->sendError(dao::getError());

            $link = $this->createLink($this->app->rawModule,'link', "id=$id&type=task&orderBy=$orderBy");
            return $this->sendSuccess(array('load' => $link, 'closeModal' => true));
        }

        /* Set browse type. */
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Get executions by product. */
        $this->loadModel('product');
        $executions    = array();
        $repo          = $this->loadModel('repo')->fetchByID($repoID);
        $productIdList = $repo ? explode(',', trim($repo->product, ',')) : array();
        foreach($productIdList as $productID)
        {
            if(empty($productID)) continue;
            $executions = $executions + $this->product->getExecutionPairsByProduct((int)$productID);
        }

        $this->loadModel('execution');
        $this->ppmZen->buildLinkTaskSearchForm($id, $repoID, $orderBy, $queryID, $executions);

        $linkedTasks = $this->ppm->getLinkList($id, 'task');

        /* Get tasks by executions. */
        $allTasks = array();
        if($browseType == 'bysearch')
        {
            $allTasks = $this->execution->getTasks(0, 0, $executions, $browseType, $queryID, 0, $orderBy, null);
        }
        else
        {
            $this->loadModel('task');
            $queryStatus = $this->lang->task->statusList;
            unset($queryStatus['closed']);

            $condition = new stdclass();
            $condition->statusList    = array_keys($queryStatus);
            $condition->executionList = array_keys($executions);
            $allTasks = $this->loadModel('task')->getListByCondition($condition);
        }

        /* Filter linked tasks. */
        $linkedTaskIDs = array_keys($linkedTasks);
        foreach($allTasks as $key => $task)
        {
            if(in_array($task->id, $linkedTaskIDs)) unset($allTasks[$key]);
        }

        $this->ppmZen->processLinkTaskPager($recTotal, $recPerPage, $pageID, $allTasks);

        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->id         = $id;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->repoID     = $repoID;
        $this->display();
    }

    /**
     * 解除合并请求关联的对象。
     * UnLink an mr link.
     *
     * @param  int    $MRID
     * @param  string $type
     * @param  int    $linkID
     * @access public
     * @return void
     */
    public function unlink(int $id, string $type, int $linkID)
    {
        $this->ppm->unlink($id, $type, $linkID);

        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->sendSuccess(array('message' => '', 'load' => $this->createLink($this->app->rawModule, 'link', "id=$id&type=$type")));
    }

    /**
     * 获取创建合并请求的检查列表。
     * AJAX get create MR check list.
     *
     * @param  int $repoID
     * @param  string $sourceBranch
     * @param  string $targetBranch
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function ajaxGetCreateCheckList(int $repoID, string $sourceBranch, string $targetBranch, int $recPerPage = 20, int $pageID = 1)
    {
        $sourceBranch = helper::safe64Decode($sourceBranch);
        $targetBranch = helper::safe64Decode($targetBranch);

        $repo = $this->loadModel('repo')->getByID($repoID);
        $scm  = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $this->app->loadClass('pager', true);
        $commitPager = new pager(0, $recPerPage, $pageID);
        $objectPager = new pager(0, $recPerPage, $pageID);

        $commits = $this->ppm->getCommitListByBranch($repo, $sourceBranch, $targetBranch, $commitPager);
        $diffs   = $scm->diff('', $targetBranch, $sourceBranch, 'yes', 'isBranchOrTag', true);
        $objects = $this->ppm->getRelationByBranch($repo, $sourceBranch, $targetBranch, '', $objectPager);

        $this->view->commits      = $commits;
        $this->view->objects      = $objects;
        $this->view->diffs        = $diffs;
        $this->view->commitPager  = $commitPager;
        $this->view->objectPager  = $objectPager;
        $this->view->repoID       = $repoID;
        $this->view->repo         = $repo;
        $this->view->sourceBranch = $sourceBranch;
        $this->view->targetBranch = $targetBranch;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        $this->display();
    }

    /**
     * 获取冲突文件列表。
     * AJAX get conflict files.
     *
     * @param  int $repoID
     * @param  string $sourceBranch
     * @param  string $targetBranch
     * @access public
     * @return void
     */
    public function ajaxGetConflictFiles(int $repoID, string $sourceBranch, string $targetBranch)
    {
        $sourceBranch = helper::safe64Decode($sourceBranch);
        $targetBranch = helper::safe64Decode($targetBranch);

        $mergeCheckMessage = $this->loadModel('gitfox')->apiGetMergeCheckMessage($repoID, $sourceBranch, $targetBranch);
        $conflictFiles     = empty($mergeCheckMessage) ? array() : zget($mergeCheckMessage, 'conflictFiles', array());

        $conflictFileList = array();
        foreach($conflictFiles as $conflictFile)
        {
            $file = new stdclass();
            $file->file = $conflictFile;
            $conflictFileList[] = $file;
        }

        $this->view->conflictFiles = $conflictFileList;
        $this->display();
    }

    /**
     * 获取审批人列表。
     * AJAX get reviewers.
     *
     * @param  int $ppmID
     * @access public
     * @return void
     */
    public function ajaxGetReviewers(int $ppmID, $type = '')
    {
        $ppm      = $this->ppm->fetchByID($ppmID);
        $flow     = $this->loadModel('reporeviewflow')->getByID(zget($ppm, 'reviewFlowID', 0));
        $reviewID = !empty($flow) && !empty($flow->definition->reviewFlow) ? $flow->definition->reviewFlow->approvals->approvalID : 0;

        $reviewers    = !empty($reviewID) ? array() : $this->ppm->getReviewers($ppmID);
        $reviewResult = $this->ppm->getReviewResult($reviewers, empty($flow) ? array() : $flow);

        $this->view->flow         = $flow;
        $this->view->ppmID        = $ppmID;
        $this->view->ppm          = $ppm;
        $this->view->reviewers    = $reviewers;
        $this->view->reviewResult = $reviewResult;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->type         = $type;
        $this->display();
    }

    /**
     * 添加审批人。
     * AJAX add reviewers.
     *
     * @param  int $ppmID
     * @param  string $callBack
     * @access public
     * @return void
     */
    public function ajaxAddReviewers(int $ppmID, string $type = 'basic')
    {
        $ppm = $this->ppm->fetchByID($ppmID);
        if($_POST)
        {
            $data = form::data($this->config->ppm->form->addReviewers)->get();
            $this->ppm->addReviewers($ppm, $data->reviewer);
            if(dao::isError()) return $this->sendError(dao::getError());

            $response = array();
            $response['result']     = 'success';
            $response['message']    = $this->lang->saveSuccess;
            $response['closeModal'] = true;
            if($type == 'basic')
            {
                $response['callback'] = "loadTarget($.createLink('ppm', 'ajaxGetReviewers', 'ppmID={$ppmID}&type=basic'), '#reviewer');";
            }
            else
            {
                $response['load'] = $this->createLink('ppm', 'view', "id={$ppmID}&type=basic");
            }
            return print $this->send($response);
        }

        $reviewers = $this->ppm->getReviewers($ppmID);
        $repo      = $this->loadModel('repo')->getByID($ppm->repoID);

        $repoMembers = array_keys($repo->members);
        $users = array();
        foreach($repoMembers as $member) if(!isset($reviewers[$member]) && $member != $this->app->user->account) $users[] = $member;
        $users = $this->loadModel('user')->getListByAccounts($users);

        $this->view->users = array_column($users, 'realname', 'account');
        $this->display();
    }

    /**
     * 删除审批人。
     * AJAX delete reviewer.
     *
     * @param  int $ppmID
     * @param  string $reviewer
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxDeleteReviewer(int $ppmID, string $reviewer, $type = 'basic')
    {
        $this->ppm->deleteReviewer($ppmID, $reviewer);
        if(dao::isError()) return $this->sendError(dao::getError());

        $response = array();
        $response['result']     = 'success';
        $response['message']    = $this->lang->saveSuccess;
        $response['closeModal'] = true;
        if($type == 'basic')
        {
            $response['callback'] = "loadTarget($.createLink('ppm', 'ajaxGetReviewers', 'ppmID={$ppmID}&type=basic'), '#reviewer');";
        }
        else
        {
            $response['load'] = $this->createLink('ppm', 'view', "id={$ppmID}&type=basic");
        }
        return print $this->send($response);
    }

    /**
     * 合并请求。
     * Merge PPM.
     *
     * @param  int $ppmID
     * @param  string $mergeType
     * @access public
     * @return void
     */
    public function merge(int $ppmID, string $mergeType)
    {
        $this->ppm->merge($ppmID, $mergeType);
        if(dao::isError()) return $this->sendError(zget(dao::getError(), 'apiMessage', $this->lang->error->httpServerError));

        $ppm = $this->ppm->fetchByID($ppmID);
        $this->ppm->logMergedAction($ppm);
        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 检查合并请求是否需要再次审批.
     * Check if need review again.
     *
     * @param  int $ppmID
     * @access public
     * @return void
     */
    public function ajaxCheckReviewFlow(int $ppmID)
    {
        $ppm = $this->ppm->fetchByID($ppmID);
        $flow = $this->loadModel('reporeviewflow')->getByID(zget($ppm, 'reviewFlowID', 0));
        if(empty($flow)) return '';

        $newCommits = $flow->definition->reviewFlow->newCommits->addressOption;
        if($newCommits != 'requireReReview') return '';

        $reviewers = $this->ppm->getReviewers($ppmID);
        if(empty($reviewers)) return '';

        $review = new stdClass();
        $review->decision = 'pending';
        $review->opinion  = '';
        $review->sha      = $ppm->sourceSHA;
        foreach($reviewers as $reviewer)
        {
            $this->ppm->review($ppmID, $review, $reviewer->account);
        }
        if(dao::isError()) return $this->sendError(dao::getError());
        return print $this->sendSuccess();
    }
}
