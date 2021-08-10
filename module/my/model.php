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
    
    public function myInfo()
    {
        $data = array();

        /* My count. */
        $data['tasks'] = (int) $this->dao->select('count(*) AS count')->from(TABLE_TASK)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->fetch('count');
        $data['stories'] = (int) $this->dao->select('count(*) AS count')->from(TABLE_STORY)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->andWhere('type')->eq('story')->fetch('count');
        $data['bugs'] = (int) $this->dao->select('count(*) AS count')->from(TABLE_BUG)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->fetch('count');

        /* Charge products. */
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
        $data['chargeProducts'] = $products;

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
        $data['projects'] = $projects;

        /* Todo list. */
        if(common::hasPriv('todo', 'view')) $hasViewPriv['todo'] = true;
        if(common::hasPriv('task', 'view')) $hasViewPriv['task'] = true;
        if(common::hasPriv('bug', 'view')) $hasViewPriv['bug'] = true;
        if(common::hasPriv('risk', 'view') and isset($this->config->maxVersion)) $hasViewPriv['risk'] = true;
        if(common::hasPriv('issue', 'view') and isset($this->config->maxVersion)) $hasViewPriv['issue'] = true;
        if(common::hasPriv('meeting', 'view') and isset($this->config->maxVersion)) $hasViewPriv['meeting'] = true;
        if(common::hasPriv('story', 'view')) $hasViewPriv['story'] = true;
        if(isset($hasViewPriv['todo']))
        {
            $this->app->loadClass('date');
            $this->app->loadLang('todo');
            $stmt = $this->dao->select('*')->from(TABLE_TODO)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('cycle')->eq(0)
                    ->andWhere('deleted')->eq(0)
                    ->andWhere('status')->eq('wait')
                    ->orderBy('`date` desc')
                    ->limit(3);
            $todos = $stmt->fetchAll();
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
            $data['todos'] = $todos;
        }
        if(isset($hasViewPriv['task']))
        {
            $this->app->loadLang('task');
            $this->app->loadLang('execution');
            $stmt = $this->dao->select('*')->from(TABLE_TASK)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('status')->ne('closed')
                    ->orderBy('id_desc')
                    ->limit(3);
            $data['toTasks'] = $stmt->fetchAll();
        }
        if(isset($hasViewPriv['bug']))
        {
            $this->app->loadLang('bug');
            $stmt = $this->dao->select('*')->from(TABLE_BUG)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('status')->ne('closed')
                    ->orderBy('id_desc')
                    ->limit(3);
            $data['toBugs'] = $stmt->fetchAll();
        }
        if(isset($hasViewPriv['risk']))
        {
            $this->app->loadLang('risk');
            $stmt = $this->dao->select('*')->from(TABLE_RISK)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('status')->ne('closed')
                    ->orderBy('id_desc')
                    ->limit(3);
            $data['toRisks'] = $stmt->fetchAll();
        }
        if(isset($hasViewPriv['meeting']))
        {
            $this->app->loadLang('meeting');
            $today = helper::today();
            $now = date('H:i:s', strtotime(helper::now()));

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

            $data['toMeetings'] = $meetings;
        }
        if(isset($hasViewPriv['issue']))
        {
            $this->app->loadLang('issue');
            $stmt = $this->dao->select('*')->from(TABLE_ISSUE)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('status')->ne('closed')
                    ->orderBy('id_desc')
                    ->limit(3);

            $data['toIssues'] = $stmt->fetchAll();
        }
        if(isset($hasViewPriv['story']))
        {
            $this->app->loadLang('story');
            $stmt = $this->dao->select('*')->from(TABLE_STORY)
                    ->where('assignedTo')->eq($this->app->user->account)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('status')->ne('closed')
                    ->orderBy('id_desc')
                    ->limit(3);
            $data['toStories'] = $stmt->fetchAll();
        }

        /* Dynamic. */
        $actions = $this->loadModel('action')->getDynamic('all', 'today', 'date_desc');
        $users = $this->loadModel('user')->getPairs('noletter');
        foreach($actions as $key => $action)
        {
            $actions[$key]->actor = $users[$action->actor];
        }
        $data['dynamic'] = $actions;

        /* User info. */
        global $lang, $app;
        $data['user'] = new stdclass();
        $data['user']->score = $app->user->score;
        $data['user']->avatar = !empty($app->user->avatar) ? $app->user->avatar : strtoupper($app->user->account[0]);
        $data['user']->role = isset($lang->user->roleList[$app->user->role]) ? $lang->user->roleList[$app->user->role] : $app->user->role;
        $data['user']->email = $app->user->email;
        $data['participateProjectCount'] = count($data['projects']);
        $data['createdDocs'] = $this->dao->select('count(*) AS count')->from(TABLE_DOC)->where('addedBy')->eq($this->app->user->account)->andWhere('deleted')->eq('0')->fetch('count');

        return $data;
    }

}
