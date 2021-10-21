<?php
/**
 * The todo activate entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class todoActivateEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function get($todoID)
    {
        $control = $this->loadController('todo', 'activate');
        $control->activate($todoID);

        $data = $this->getData();
        if($data->status == 'fail') return $this->sendError(400, $data->message);

        $todo = $this->loadModel('todo')->getByID($todoID);
        $this->send(200, $this->format($todo, 'assignedDate:time,finishedDate:time,closedDate:time'));
    }
}
