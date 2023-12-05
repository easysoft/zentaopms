<?php
/**
 * The tasks entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class tasksEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function get($executionID = 0)
    {
        /* Get tasks by search, search arguments available: pri, assignedTo, status, id, name. Pager arguments will be utilized as well. */
        if($this->param('search', 0) == 1) // TODO: document this api.
        {
            $this->loadModel('task');
            $searchParams = array();
            foreach(array('pri' => 'priList', 'assignedTo' => 'assignedToList', 'status' => 'statusList', 'id' => 'idList', 'name' => 'taskName') as $field => $condName)
            {
                if($this->param($field, false))
                {
                    $searchParams[$condName] = $this->param($field);
                    continue;
                }
                if($this->param($condName, false)) $searchParams[$condName] = $this->param($condName);
            }

            $this->app->loadClass('pager', $static = true);
            $pager = pager::init($this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
            $tasks = $this->task->getListByCondition((object)$searchParams, $this->param('order', 'id_desc'), $pager);

            $data = new stdclass();
            $data->status = 'success';
            $data->data   = new stdclass();
            $data->data->tasks = array_values($tasks);
            $data->data->pager = (object)$pager;
        }
        elseif(!$executionID)
        {
            /* Get my tasks defaultly. */
            $control = $this->loadController('my', 'task');
            $control->task($this->param('type', 'assignedTo'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
            $data = $this->getData();
        }
        else
        {
            /* If $executionID is a project that have no execution, get real executionID. */
            $project = $this->loadModel('project')->getByID($executionID);
            if($project and $project->type == 'project' and !$project->multiple)
            {
                $executions = $this->loadModel('execution')->getList($project->id);
                foreach($executions as $execution)
                {
                    if(!$execution->multiple)
                    {
                        $executionID = $execution->id;
                        break;
                    }
                }
            }

            /* Get tasks by execution. */
            $control = $this->loadController('execution', 'task');
            $control->task($executionID, $this->param('status', 'all'), 0, $this->param('order', 'id_desc'), 0, $this->param('limit', 100), $this->param('page', 1));
            $data = $this->getData();
        }

        if(isset($data->status) and $data->status == 'success')
        {
            $tasks  = $data->data->tasks;
            $pager  = $data->data->pager;
            $result = array();
            foreach($tasks as $task)
            {
                if(isset($task->children)) $task->children = array_values((array)$task->children);
                $result[] = $this->format($task, 'deadline:date,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,realStarted:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool,mailto:userList');
            }
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'tasks' => $result));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function post($executionID)
    {
        $fields = 'name,type,assignedTo,estimate,story,execution,project,module,pri,desc,estStarted,deadline,mailto,team,teamEstimate,multiple,uid';
        $this->batchSetPost($fields);

        $assignedTo = $this->request('assignedTo', array(0 => ''));
        if($assignedTo and !is_array($assignedTo)) $assignedTo = array($assignedTo);
        $this->setPost('assignedTo', $assignedTo);

        if($this->request('multiple'))
        {
            if(count($this->request('team')) != count($this->request('teamEstimate'))) return $this->sendError(400, 'Arrays team and teamEstimate should be the same length');
            $this->setPost('mode', $this->request('mode', 'linear'));
            $this->setPost('teamSource', array_fill(0, count($this->request('team')), ''));
        }

        $this->setPost('execution', $executionID);

        $control = $this->loadController('task', 'create');
        $this->requireFields('name,assignedTo,type,estStarted,deadline');

        $control->create($executionID, $this->request('storyID', 0), $this->request('moduleID', 0), $this->request('copyTaskID', 0), $this->request('copyTodoID', 0));

        $data = $this->getData();
        if(!isset($data->id)) return $this->sendError(400, $data->message);

        $task = $this->loadModel('task')->getByID($data->id);

        return $this->send(201, $this->format($task, 'deadline:date,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,realStarted:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool,mailto:userList'));
    }
}
