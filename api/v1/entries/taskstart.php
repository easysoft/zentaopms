<?php
/**
 * The task start entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class taskStartEntry extends Entry
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

        $fields = 'assignedTo,realStarted,comment,left';
        $this->batchSetPost($fields);

        $control = $this->loadController('task', 'start');
        $this->requireFields('left');
        $control->start($taskID);
        
        $data = $this->getData();
        if($data->result == 'fail') return $this->sendError(400, $data->message);

        $task = $this->loadModel('task')->getByID($taskID);

        $this->send(200, $task);
    }
}
