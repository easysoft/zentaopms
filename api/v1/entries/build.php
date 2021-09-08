<?php
/**
 * The build entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class buildEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function get($buildID)
    {
        $control = $this->loadController('build', 'view');
        $control->view($buildID);

        $data = $this->getData();
        if(!$data or (isset($data->status) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->status) and $data->status == 'success') return $this->send(200, $data->data->build);
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $this->sendError(400, 'error');
    }

    /**
     * PUT method.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function put($buildID)
    {
        $old = $this->loadModel('build')->getByID($buildID);

        /* Set $_POST variables. */
        $fields = 'type,title,severity,pri,assignedTo,deadline,desc';
        $this->batchSetPost($fields, $oldBuild);

        $control = $this->loadController('build', 'edit');
        $control->edit($buildID);

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $build = $this->build->getByID($buildID);
        $this->send(200, $this->format($build, 'createdDate:time,editedDate:time,assignedDate:time'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function delete($buildID)
    {
        $control = $this->loadController('build', 'delete');
        $control->delete($buildID, 'true');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
