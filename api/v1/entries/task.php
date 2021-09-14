<?php
/**
 * The task entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class taskEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function get($taskID)
    {
        $control = $this->loadController('task', 'view');
        $control->view($taskID);

        $data = $this->getData();

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail')
        {
            return isset($data->code) and $data->code == 404 ? $this->send404() : $this->sendError(400, $data->message);
        }

        $task = $data->data->task;
        $this->send(200, $this->format($task, 'openedDate:time,assignedDate:time,realStarted:time,finishedDate:time,canceledDate:time,closedDate:time,lastEditedDate:time,deleted:bool'));
    }

    /**
     * PUT method.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function put($taskID)
    {
        $oldTask = $this->loadModel('task')->getByID($taskID);

        /* Set $_POST variables. */
        $fields = 'name,type,assignedTo,estimate,left,consumed,story,parent,execution,module,closedReason,status,estStarted,deadline';
        $this->batchSetPost($fields, $oldTask);

        $control = $this->loadController('task', 'edit');
        $control->edit($taskID);

        $this->getData();
        $task = $this->task->getByID($taskID);
        $this->send(200, $this->format($task, 'openedDate:time,assignedDate:time,realStarted:time,finishedDate:time,canceledDate:time,closedDate:time,lastEditedDate:time'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function delete($taskID)
    {
        $control = $this->loadController('task', 'delete');
        $control->delete(0, $taskID, 'true');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
