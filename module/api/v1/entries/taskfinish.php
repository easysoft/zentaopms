<?php
/**
 * The task finish entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class taskFinishEntry extends entry
{
    /**
     * POST method.
     *
     * @param  int    $taskID
     * @access public
     * @return string
     */
    public function post($taskID)
    {
        $task = $this->loadModel('task')->getByID($taskID);

        $fields = 'assignedTo,realStarted';
        $this->batchSetPost($fields, $task);

        $fields = 'finishedDate,comment';
        $this->batchSetPost($fields);

        $realStarted = $this->request('realStarted', (isset($task->realStarted) and !helper::isZeroDate($task->realStarted)) ? $task->realStarted : '');
        if($realStarted) $this->setPost('realStarted', $realStarted);
        $this->setPost('currentConsumed', $this->request('currentConsumed', 0));
        $this->setPost('consumed', $this->request('currentConsumed', 0) + $task->consumed);

        $control = $this->loadController('task', 'finish');
        $this->requireFields('currentConsumed,realStarted,finishedDate');
        $control->finish($taskID);

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $task = $this->loadModel('task')->getByID($taskID);

        return $this->send(200, $this->format($task, 'openedDate:time,assignedDate:time,realStarted:time,finishedDate:time,canceledDate:time,closedDate:time,lastEditedDate:time'));
    }
}
