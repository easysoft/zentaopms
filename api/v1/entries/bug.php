<?php
/**
 * The bug entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class bugEntry extends entry 
{
    /**
     * GET method.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function get($bugID)
    {
        $control = $this->loadController('bug', 'view');
        $control->view($bugID);

        $data = $this->getData();
        $bug  = $data->data->bug;
        $this->send(200, $this->format($bug, 'deleted:bool,activatedDate:time,openedDate:time,assignedDate:time,resolvedDate:time,closedDate:time,lastEditedDate:time'));
    }

    /**
     * PUT method.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function put($bugID)
    {
        $oldBug = $this->loadModel('bug')->getByID($bugID);

        /* Set $_POST variables. */
        $fields = 'title,project,execution,openedBuild,assignedTo,pri,severity,type,story,resolvedBy,closedBy,resolution,product,plan,task';
        $this->batchSetPost($fields, $oldBug);

        $control = $this->loadController('bug', 'edit');
        $control->edit($bugID);

        $data = $this->getData();

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->status)) return $this->sendError(400, 'error');

        $bug = $this->bug->getByID($bugID);
        $this->send(200, $this->format($bug, 'activatedDate:time,openedDate:time,assignedDate:time,resolvedDate:time,closedDate:time,lastEditedDate:time'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function delete($bugID)
    {
        $control = $this->loadController('bug', 'delete');
        $control->delete($bugID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
