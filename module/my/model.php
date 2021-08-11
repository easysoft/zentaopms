<?php
/**
 * The model file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class myModel extends model
{
    /**
     * Set menu.
     * 
     * @access public
     * @return void
     */
    public function setMenu()
    {
        /* Adjust the menu order according to the user role. */
        $flowModule = $this->config->global->flow . '_my';
        $customMenu = isset($this->config->customMenu->$flowModule) ? $this->config->customMenu->$flowModule : array();

        if(empty($customMenu))
        {
            $role = $this->app->user->role;
            if($role == 'qa')
            {
                $taskOrder = '15';
                $bugOrder  = '20';

                unset($this->lang->my->menuOrder[$taskOrder]);
                $this->lang->my->menuOrder[32] = 'task';
                $this->lang->my->dividerMenu = str_replace(',task,', ',' . $this->lang->my->menuOrder[$bugOrder] . ',', $this->lang->my->dividerMenu);
            }
            elseif($role == 'po')
            {
                $requirementOrder = 29;
                unset($this->lang->my->menuOrder[$requirementOrder]);

                $this->lang->my->menuOrder[15] = 'story';
                $this->lang->my->menuOrder[16] = 'requirement';
                $this->lang->my->menuOrder[30] = 'task';
                $this->lang->my->dividerMenu = str_replace(',task,', ',story,', $this->lang->my->dividerMenu);
            }
            elseif($role == 'pm')
            {
                $projectOrder = 35;
                unset($this->lang->my->menuOrder[$projectOrder]);

                $this->lang->my->menuOrder[17] = 'myProject';
            }
        }
    }
    
    /**
     * Get my info.
     *
     * @access public
     * @return object
     */
    public function getInfo()
    {
        $info = new stdclass();
        $info->profile = $this->loadModel('user')->getById($this->app->user->account);

        /* My tasks. */
        $info->task = new stdclass();
        $info->task->total   = (int) $this->dao->select('count(*) AS count')->from(TABLE_TASK)->where('assignedTo')->eq($this->app->user->account)->andWhere('status')->ne('closed')->andWhere('deleted')->eq(0)->fetch('count');
        $info->task->dynamic = array();

        if(common::hasPriv('task', 'view'))
        {
            $tasks = $this->dao->select('*')->from(TABLE_TASK)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('status')->ne('closed')
                    ->orderBy('id_desc')
                    ->limit(3)
                    ->fetchAll();
            $info->task->dynamic = $tasks;
        }

        /* My stories. */
        $info->story = new stdclass();
        $info->story->total   = (int) $this->dao->select('count(*) AS count')->from(TABLE_STORY)->where('assignedTo')->eq($this->app->user->account)->andWhere('status')->ne('closed')->andWhere('deleted')->eq(0)->andWhere('type')->eq('story')->fetch('count');
        $info->story->dynamic = array();
        if(common::hasPriv('story', 'view'))
        {
            $stories = $this->dao->select('*')->from(TABLE_STORY)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('status')->ne('closed')
                    ->orderBy('id_desc')
                    ->limit(3)
                    ->fetchAll();
            $info->story->dynamic = $stories;
        }

        /* My bugs. */
        $info->bug = new stdclass();
        $info->bug->total   = (int) $this->dao->select('count(*) AS count')->from(TABLE_BUG)->where('assignedTo')->eq($this->app->user->account)->andWhere('status')->ne('closed')->andWhere('deleted')->eq(0)->fetch('count');
        $info->bug->dynamic = array();
        if(common::hasPriv('bug', 'view'))
        {
            $this->app->loadLang('bug');
            $bugs = $this->dao->select('*')->from(TABLE_BUG)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('status')->ne('closed')
                    ->orderBy('id_desc')
                    ->limit(3)
                    ->fetchAll();
            $info->bug->dynamic = $bugs;
        }

        /* My todos. */
        $info->todo = new stdclass();
        $info->todo->total   = (int) $this->dao->select('count(*) AS count')->from(TABLE_TODO)->where('assignedTo')->eq($this->app->user->account)->andWhere('status')->ne('closed')->andWhere('deleted')->eq(0)->fetch('count');
        $info->todo->dynamic = new stdclass();
        if(common::hasPriv('todo', 'view'))
        {
            $todos = $this->dao->select('*')->from(TABLE_TODO)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('cycle')->eq(0)
                    ->andWhere('deleted')->eq(0)
                    ->andWhere('status')->eq('wait')
                    ->orderBy('`date` desc')
                    ->limit(3)
                    ->fetchAll();
            foreach($todos as $key => $todo)
            {
                if($todo->status == 'done' and $todo->finishedBy == $this->app->user->account)
                {
                    unset($todos[$key]);
                    continue;
                }

                $todo->begin = date::formatTime($todo->begin);
                $todo->end = date::formatTime($todo->end);
            }
            $info->todo->dynamic = $todos;
        }

        /* My risks. */
        $info->risk = new stdclass();
        $info->risk->total   = 0;
        $info->risk->dynamic = new stdclass();
        if(common::hasPriv('risk', 'view') and isset($this->config->maxVersion))
        {
            $risks = $this->dao->select('*')->from(TABLE_RISK)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('status')->ne('closed')
                    ->orderBy('id_desc')
                    ->limit(3)
                    ->fetchAll();
            $info->risk->total   = (int) $this->dao->select('count(*) AS count')->from(TABLE_RISK)->where('assignedTo')->eq($this->app->user->account)->andWhere('status')->ne('closed')->andWhere('deleted')->eq(0)->fetch('count');
            $info->risk->dynamic = $risks;
        }

        /* My meetings. */
        $info->meeting = new stdclass();
        $info->meeting->total   = 0;
        $info->meeting->dynamic = new stdclass();
        if(common::hasPriv('meeting', 'view') and isset($this->config->maxVersion))
        {
            $today = helper::today();
            $now   = date('H:i:s', strtotime(helper::now()));

            $meetings = $this->dao->select('*')->from(TABLE_MEETING)
                    ->Where('deleted')->eq('0')
                    ->andWhere('(date')->gt($today)
                    ->orWhere('(begin')->gt($now)
                    ->andWhere('date')->eq($today)
                    ->markRight(2)
                    ->andwhere('(host')->eq($this->app->user->account)
                    ->orWhere('participant')->in($this->app->user->account)
                    ->markRight(1)
                    ->orderBy('id_desc')
                    ->limit(3)
                    ->fetchAll();
            $info->meeting->total   = (int) $this->dao->select('count(*) AS count')->from(TABLE_MEETING)->where('assignedTo')->eq($this->app->user->account)->andWhere('status')->ne('closed')->andWhere('deleted')->eq(0)->fetch('count');
            $info->meeting->dynamic = $meetings;
        }

        /* My issues. */
        $info->issue = new stdclass();
        $info->issue->total   = 0;
        $info->issue->dynamic = new stdclass();
        if(common::hasPriv('issue', 'view') and isset($this->config->maxVersion))
        {
            $issues = $this->dao->select('*')->from(TABLE_ISSUE)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('status')->ne('closed')
                    ->orderBy('id_desc')
                    ->limit(3)
                    ->fetchAll();
            $info->issue->total   = (int) $this->dao->select('count(*) AS count')->from(TABLE_ISSUE)->where('assignedTo')->eq($this->app->user->account)->andWhere('status')->ne('closed')->andWhere('deleted')->eq(0)->fetch('count');
            $info->issue->dynamic = $issues;
        }

        /* My charged products. */
        $products = $this->dao->select('t1.id as id,t1.*')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
                ->where('t1.deleted')->eq(0)
                ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
                ->andWhere('t1.status')->ne('closed')
                ->andWhere('t1.PO')->eq($this->app->user->account)
                ->orderBy('t1.order_asc')
                ->fetchAll('id');
        $productKeys = array_keys($products);

        $stories = $this->dao->select('product, status, count(status) AS count')
                ->from(TABLE_STORY)
                ->where('deleted')->eq(0)
                ->andWhere('product')->in($productKeys)
                ->groupBy('product, status')
                ->fetchGroup('product', 'status');
        $plans = $this->dao->select('product, count(*) AS count')
                ->from(TABLE_PRODUCTPLAN)
                ->where('deleted')->eq(0)
                ->andWhere('product')->in($productKeys)
                ->andWhere('end')->gt(helper::now())
                ->groupBy('product')
                ->fetchPairs();
        $releases = $this->dao->select('product, count(*) AS count')
                ->from(TABLE_RELEASE)
                ->where('deleted')->eq(0)
                ->andWhere('product')->in($productKeys)
                ->groupBy('product')
                ->fetchPairs();
        $executions = $this->dao->select('t1.product,t2.id,t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->in($productKeys)
                ->andWhere('t2.type')->in('stage,sprint')
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy('t1.project')
                ->fetchAll('product');
        foreach($executions as $key => $execuData)
        {
            $execution = $this->loadModel('execution')->getById($execuData->id);
            $executions[$key]->progress = ($execution->totalConsumed + $execution->totalLeft) ? floor($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft) * 1000) / 1000 * 100 : 0;
        }

        foreach($products as $key => $product)
        {
            $product->plans = isset($plans[$product->id]) ? $plans[$product->id] : 0;
            $product->releases = isset($releases[$product->id]) ? $releases[$product->id] : 0;
            if(isset($stories[$product->id])) $product->stories = $stories[$product->id];
            if(isset($executions[$product->id])) $product->executions = $executions[$product->id];
        }
        $info->products = array_values($products);

        /* All projects. */
        $projects = $this->loadModel('project')->getOverviewList('byStatus', 'all', 'order_asc');
        foreach($projects as $key => $project)
        {
            if($project->status == 'doing')
            {
                $workhour = $this->loadModel('project')->getWorkhour($project->id);
                $projects[$key]->progress = ($workhour->totalConsumed + $workhour->totalLeft) ? floor($workhour->totalConsumed / ($workhour->totalConsumed + $workhour->totalLeft) * 1000) / 1000 * 100 : 0;
            }
        }
        $info->projects = array_values($projects);

        /* Dynamic. */
        $actions = $this->loadModel('action')->getDynamic('all', 'today', 'date_desc');
        $users = $this->loadModel('user')->getPairs('noletter');
        foreach($actions as $key => $action)
        {
            $actions[$key]->actor = $users[$action->actor];
        }
        $info->dynamic = $actions;

        /* User info. */
        $info->participateProjectCount = count($info->projects);
        $info->createdDocs = $this->dao->select('count(*) AS count')->from(TABLE_DOC)->where('addedBy')->eq($this->app->user->account)->andWhere('deleted')->eq('0')->fetch('count');

        return $info;
    }

}
