<?php
/**
 * The task batch create entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class taskBatchCreateEntry extends entry
{
    /**
     * POST method.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function post($executionID = 0)
    {
        if(!$executionID) $executionID = $this->param('execution', 0);
        if(!$executionID) return $this->send400('Need execution id.');

        $storyID  = $this->param('story', 0);
        $moduleID = $this->param('module', 0);
        $taskID   = $this->param('task', 0);

        if(!isset($this->requestBody->tasks))
        {
            return $this->send400('Need tasks.');
        }

        $modules    = array();
        $parents    = array();
        $names      = array();
        $colors     = array();
        $types      = array();
        $estimates  = array();
        $estStarted = array();
        $deadlines  = array();
        $desc       = array();
        $pri        = array();
        $stories    = array();
        foreach($this->request('tasks') as $key => $task)
        {
            $number = $key + 1;
            if(!isset($task->name) or !isset($task->type)) return $this->send400('Task must have name and type.');

            $modules[$number]    = isset($task->module)     ? $task->module     : $moduleID;
            $parents[$number]    = isset($task->parent)     ? $task->parent     : $taskID;
            $names[$number]      = $task->name;
            $colors[$number]     = isset($task->color)      ? $task->color      : '';
            $types[$number]      = $task->type;
            $estimates[$number]  = isset($task->estimate)   ? $task->estimate   : 0;
            $estStarted[$number] = isset($task->estStarted) ? $task->estStarted : 0;
            $deadlines[$number]  = isset($task->deadline)   ? $task->deadline   : null;
            $desc[$number]       = isset($task->desc)       ? $task->desc       : '';
            $pri[$number]        = isset($task->pri)        ? $task->pri        : 0;
            $stories[$number]    = isset($task->story)      ? $task->story      : $storyID;
        }
        $this->setPost('module',     $modules);
        $this->setPost('parent',     $parents);
        $this->setPost('name',       $names);
        $this->setPost('color',      $colors);
        $this->setPost('type',       $types);
        $this->setPost('estimate',   $estimates);
        $this->setPost('estStarted', $estStarted);
        $this->setPost('deadline',   $deadlines);
        $this->setPost('desc',       $desc);
        $this->setPost('pri',        $pri);
        $this->setPost('story',      $stories);

        $control = $this->loadController('task', 'batchCreate');
        $control->batchCreate($executionID, $storyID, $moduleID, $taskID);

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->data->result) and $data->data->result == 'fail') return $this->sendError(400, $data->data->message);

        $tasks = $this->loadModel('task')->getByIdList($data->idList);
        return $this->send(200, array('task' => $tasks));
    }
}
