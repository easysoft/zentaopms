<?php
/**
 * The execution entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class executionEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function get($executionID)
    {
        $fields = $this->param('fields');

        $control = $this->loadController('execution', 'view');
        $control->view($executionID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail')
        {
            return isset($data->code) and $data->code == 404 ? $this->send404() : $this->sendError(400, $data->message);
        }

        $execution = $this->format($data->data->execution, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time');
        if(!$fields) $this->send(200, $execution);

        /* Set other fields. */
        $fields = explode(',', $fields);
        foreach($fields as $field)
        {
            switch($field)
            {
                case 'modules':
                    $control = $this->loadController('tree', 'browsetask');
                    $control->browsetask($executionID);
                    $data = $this->getData();
                    if(isset($data->status) and $data->status == 'success')
                    {
                        $execution->modules = $data->data->tree;
                    }
                    break;
            }
        }

        return $this->send(200, $execution);
    }

    /**
     * PUT method.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function put($executionID)
    {
        $oldExecution = $this->loadModel('execution')->getByID($executionID);

        /* Set $_POST variables. */
        $fields = 'project,code,name,begin,end,lifetime,desc,days,acl';
        $this->batchSetPost($fields, $oldExecution);

        $this->setPost('whitelist', $this->request('whitelist', explode(',', $oldExecution->whitelist)));

        $control = $this->loadController('execution', 'edit');
        $control->edit($executionID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $execution = $this->execution->getByID($executionID);
        $this->send(200, $this->format($execution, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function delete($executionID)
    {
        $control = $this->loadController('execution', 'delete');
        $control->delete($executionID, 'true');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
