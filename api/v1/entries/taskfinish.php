<?php
/**
 * The task finish entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class taskFinishEntry extends Entry
{
    /**
     * POST method.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function post($taskID)
    {
        $task = $this->loadModel('task')->getByID($taskID);

        $fields = 'assignedTo,realStarted';
        $this->batchSetPost($fields, $task);

        $fields = 'finishedDate,comment';
        $this->batchSetPost($fields);

        $this->setPost('currentConsumed', $this->request('currentConsumed', 0));
        $this->setPost('consumed', $this->request('currentConsumed', 0) + $task->consumed);

        $control = $this->loadController('task', 'finish');
        $this->requireFields('assignedTo,currentConsumed,realStarted,finishedDate');
        $control->finish($taskID);

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $task = $this->loadModel('task')->getByID($taskID);

        $this->send(200, $this->format($task, 'openedDate:time,assignedDate:time,realStarted:time,finishedDate:time,canceledDate:time,closedDate:time,lastEditedDate:time'));
    }
}
