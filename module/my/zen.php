<?php
declare(strict_types=1);
/**
 * The zen file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
class myZen extends my
{
    /**
     * 构造任务数据。
     * Build task data.
     *
     * @param  array     $tasks
     * @access protected
     * @return array
     */
    protected function buildTaskData(array $tasks): array
    {
        $parents = array();
        foreach($tasks as $task)
        {
            if(isset($tasks[$task->parent]) || $task->parent <= 0 || isset($parents[$task->parent])) continue;
            $parents[$task->parent] = $task->parent;
        }

        $parentPairs = $this->loadModel('task')->getPairsByIdList($parents);
        foreach($tasks as $task)
        {
            $task->estimateLabel = $task->estimate . $this->lang->execution->workHourUnit;
            $task->consumedLabel = $task->consumed . $this->lang->execution->workHourUnit;
            $task->leftLabel     = $task->left     . $this->lang->execution->workHourUnit;
            $task->status        = !empty($task->storyStatus) && $task->storyStatus == 'active' && $task->latestStoryVersion > $task->storyVersion && !in_array($task->status, array('cancel', 'closed')) ? 'changed' : $task->status;
            $task->canBeChanged  = common::canBeChanged('task', $task);
            $task->isChild       = false;
            $task->parentName    = '';

            if($task->status == 'changed') $task->rawStatus = 'changed';
            if($task->parent > 0)
            {
                if(isset($tasks[$task->parent]))
                {
                    $tasks[$task->parent]->hasChild = true;
                }
                else
                {
                    $task->name    = zget($parentPairs, $task->parent, '') . ' / ' . $task->name;
                    $task->isChild = true;
                    $task->parent  = 0;
                }
            }
        }
        return $tasks;
    }

    /**
     * 构造用例数据。
     * Build case data.
     *
     * @param  array     $cases
     * @param  string    $type  assigntome|openedbyme
     * @access protected
     * @return array
     */
    protected function buildCaseData(array $cases, string $type): array
    {
        $cases = $this->loadModel('story')->checkNeedConfirm($cases);
        $cases = $this->loadModel('testcase')->appendData($cases, $type == 'assigntome' ? 'run' : 'case');

        $failCount = 0;
        foreach($cases as $case)
        {
            if($case->lastRunResult && $case->lastRunResult != 'pass') $failCount ++;
            if($case->needconfirm)
            {
                $case->status = $this->lang->story->changed;
            }
            else if(isset($case->fromCaseVersion) and $case->fromCaseVersion > $case->version and !$case->needconfirm)
            {
                $case->status = $this->lang->testcase->changed;
            }
            if(!$case->lastRunResult) $case->lastRunResult = $this->lang->testcase->unexecuted;
        }
        $this->view->failCount = $failCount;
        return $cases;
    }

    /**
     * 构造反馈关联的数据。
     * Build related data.
     *
     * @param  array     $feedbacks
     * @access protected
     * @return void
     */
    protected function assignRelatedData(array $feedbacks) : void
    {
        $storyIdList = $bugIdList = $todoIdList = $taskIdList = $ticketIdList = array();
        foreach($feedbacks as $feedback)
        {
            if($feedback->solution == 'tobug')   $bugIdList[]    = $feedback->result;
            if($feedback->solution == 'tostory') $storyIdList[]  = $feedback->result;
            if($feedback->solution == 'totodo')  $todoIdList[]   = $feedback->result;
            if($feedback->solution == 'totask')  $taskIdList[]   = $feedback->result;
            if($feedback->solution == 'ticket')  $ticketIdList[] = $feedback->result;
        }
        $bugs    = $bugIdList    ? $this->loadModel('bug')->getByIdList($bugIdList) : array();
        $stories = $storyIdList  ? $this->loadModel('story')->getByList($storyIdList) : array();
        $todos   = $todoIdList   ? $this->loadModel('todo')->getByList($todoIdList) : array();
        $tasks   = $taskIdList   ? $this->loadModel('task')->getByIdList($taskIdList) : array();
        $tickets = $ticketIdList ? $this->loadModel('ticket')->getByList($ticketIdList) : array();

        $this->view->bugs    = $bugs;
        $this->view->todos   = $todos;
        $this->view->stories = $stories;
        $this->view->tasks   = $tasks;
        $this->view->tickets = $tickets;
    }

    /**
     * 构造反馈的搜索表单。
     * Build search form for feedback.
     *
     * @param  int       $queryID
     * @param  string    $orderBy
     * @access protected
     * @return void
     */
    protected function buildSearchFormForFeedback(int $queryID, string $orderBy): void
    {
        $rawMethod = $this->app->rawMethod;
        $this->config->feedback->search['module']    = $rawMethod . 'Feedback';
        $this->config->feedback->search['actionURL'] = inlink($rawMethod, "mode=feedback&browseType=bysearch&param=myQueryID&orderBy={$orderBy}");
        $this->config->feedback->search['queryID']   = $queryID;
        $this->config->feedback->search['params']['product']['values']     = $this->loadModel('product')->getPairs();
        $this->config->feedback->search['params']['module']['values']      = $this->loadModel('tree')->getOptionMenu(0, 'feedback', 0);
        $this->config->feedback->search['params']['processedBy']['values'] = $this->loadModel('feedback')->getFeedbackPairs('admin');

        if($rawMethod == 'work')
        {
            unset($this->config->feedback->search['fields']['assignedTo']);
            unset($this->config->feedback->search['fields']['closedBy']);
            unset($this->config->feedback->search['fields']['closedDate']);
            unset($this->config->feedback->search['fields']['closedReason']);
            unset($this->config->feedback->search['fields']['processedBy']);
            unset($this->config->feedback->search['fields']['processedDate']);
            unset($this->config->feedback->search['fields']['solution']);
        }

        $this->loadModel('search')->setSearchParams($this->config->feedback->search);
    }

    /**
     * 展示待处理的数量。
     * Show to-do work count.
     *
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return array
     */
    public function showWorkCount(int $recTotal = 0, int $recPerPage = 20, int $pageID = 1): array
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $count = array('task' => 0, 'story' => 0, 'bug' => 0, 'case' => 0, 'testtask' => 0, 'requirement' => 0, 'epic' => 0, 'issue' => 0, 'risk' => 0, 'reviewissue' => 0, 'qa' => 0, 'meeting' => 0, 'ticket' => 0, 'feedback' => 0);

        /* Get the number of tasks assigned to me. */
        $this->loadModel('task')->getUserTasks($this->app->user->account, 'assignedTo', 0, $pager);
        $count['task'] = $pager->recTotal;

        /* Get the number of stories assigned to me. */
        $pager->recTotal = 0;
        $this->loadModel('story')->getUserStories($this->app->user->account, 'assignedTo', 'id_desc', $pager, 'story', false, 'all');
        $assignedToStoryCount = $pager->recTotal;

        $pager->recTotal = 0;
        $this->story->getUserStories($this->app->user->account, 'reviewBy', 'id_desc', $pager, 'story', false, 'all');
        $reviewByStoryCount = $pager->recTotal;
        $count['story']     = $assignedToStoryCount + $reviewByStoryCount;

        $isOpenedURAndSR  = $this->config->URAndSR ? 1 : 0;
        if($isOpenedURAndSR)
        {
            /* Get the number of requirements assigned to me. */
            $pager->recTotal = 0;
            $this->story->getUserStories($this->app->user->account, 'assignedTo', 'id_desc', $pager, 'requirement', false, 'all');
            $assignedRequirementCount = $pager->recTotal;

            $pager->recTotal = 0;
            $this->story->getUserStories($this->app->user->account, 'reviewBy', 'id_desc', $pager, 'requirement', false, 'all');
            $reviewByRequirementCount = $pager->recTotal;
            $count['requirement']     = $assignedRequirementCount + $reviewByRequirementCount;
        }

        if($this->config->enableER)
        {
            $pager->recTotal = 0;
            $this->story->getUserStories($this->app->user->account, 'assignedTo', 'id_desc', $pager, 'epic', false, 'all');
            $assignedEpicCount = $pager->recTotal;

            $pager->recTotal = 0;
            $this->story->getUserStories($this->app->user->account, 'reviewBy', 'id_desc', $pager, 'epic', false, 'all');
            $reviewByEpicCount = $pager->recTotal;
            $count['epic']     = $assignedEpicCount + $reviewByEpicCount;
        }

        /* Get the number of bugs assigned to me. */
        $pager->recTotal = 0;
        $this->loadModel('bug')->getUserBugs($this->app->user->account, 'assignedTo', 'id_desc', 0, $pager);
        $count['bug'] = $pager->recTotal;

        /* Get the number of testcases assigned to me. */
        $pager->recTotal = 0;
        $this->loadModel('testcase')->getByAssignedTo($this->app->user->account, 'skip', 'id_desc', $pager);
        $count['case'] = $pager->recTotal;

        /* Get the number of testtasks assigned to me. */
        $pager->recTotal = 0;
        $this->loadModel('testtask')->getByUser($this->app->user->account, $pager, 'id_desc', 'wait');
        $count['testtask'] = $pager->recTotal;

        $pager->recTotal = 0;
        $count = $this->showWorkCountNotInOpen($count, $pager);

        $this->view->todoCount       = $count;
        $this->view->isOpenedURAndSR = $isOpenedURAndSR;
        return $count;
    }

    /**
     * 展示非开源版的待处理的数量。
     * Show to-do work count that not in open edition.
     *
     * @param  array  $count
     * @param  object $pager
     * @access public
     * @return array
     */
    protected function showWorkCountNotInOpen(array $count, object $pager): array
    {
        $isBiz = $this->config->edition == 'biz' ? 1 : 0;
        $isMax = $this->config->edition == 'max' ? 1 : 0;
        $isIPD = $this->config->edition == 'ipd' ? 1 : 0;

        if($this->config->edition != 'open')
        {
            $this->loadModel('feedback')->getList('assigntome', 'id_desc', $pager);
            $count['feedback'] = $pager->recTotal;

            $pager->recTotal = 0;
            $this->session->set('ticketBrowseType', 'assignedtome', 'feedback');
            $this->loadModel('ticket')->getList('assignedtome', 'id_desc', $pager);
            $count['ticket'] = $pager->recTotal;
        }

        if($isMax || $isIPD)
        {
            if($this->config->vision != 'or')
            {
                /* Get the number of issues assigned to me. */
                $pager->recTotal = 0;
                $this->loadModel('issue')->getUserIssues('assignedTo', 0, $this->app->user->account, 'id_desc', $pager);
                $count['issue'] = $pager->recTotal;

                /* Get the number of risks assigned to me. */
                $pager->recTotal = 0;
                $this->loadModel('risk')->getUserRisks('assignedTo', $this->app->user->account, 'id_desc', $pager);
                $count['risk'] = $pager->recTotal;

                /* Get the number of reviewissues assigned to me. */
                $pager->recTotal = 0;
                $this->loadModel('reviewissue')->getUserReviewissues('assignedTo', $this->app->user->account, 'id_desc', $pager);
                $count['reviewissue'] = $pager->recTotal;

                /* Get the number of nc assigned to me. */
                $pager->recTotal = 0;
                $this->my->getNcList('assignedToMe', 'id_desc', $pager, 'active');
                $ncCount = $pager->recTotal;

                /* Get the number of nc assigned to me. */
                $pager->recTotal = 0;
                $this->loadModel('auditplan')->getList(0, 'myChecking', '', 'id_desc', $pager);
                $auditplanCount = $pager->recTotal;
                $count['qa']    = $ncCount + $auditplanCount;

                /* Get the number of meetings assigned to me. */
                $pager->recTotal = 0;
                $this->loadModel('meeting')->getListByUser('futureMeeting', 'id_desc', 0, $pager);
                $count['meeting'] = $pager->recTotal;
            }

            if($isIPD && $this->config->vision == 'or')
            {
                /* Get the number of demands assigned to me. */
                $pager->recTotal = 0;
                $this->loadModel('demand')->getUserDemands($this->app->user->account, 'assignedTo', 'id_desc', $pager);
                $assignedToDemandCount = $pager->recTotal;

                /* Get the number of demands review by me. */
                $pager->recTotal = 0;
                $this->loadModel('demand')->getUserDemands($this->app->user->account, 'reviewBy', 'id_desc', $pager);
                $reviewByDemandCount = $pager->recTotal;

                $count['demand'] = $assignedToDemandCount + $reviewByDemandCount;
            }
        }

        $this->view->isBiz = $isBiz;
        $this->view->isMax = $isMax;
        $this->view->isIPD = $isIPD;

        return $count;
    }
}
