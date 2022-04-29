<?php
/**
 * The product entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class feedbackEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function get($feedbackID)
    {
        $control = $this->loadController('feedback', 'adminiView');
        $control->adminView($feedbackID);

        $data = $this->getData();

        $feedback = $data->data->feedback;

        $feedback->publicStatus = $feedback->public;
        $feedback->productName  = $data->data->product;
        $feedback->moduleName   = isset($data->data->modulePath[0]->name) ? $data->data->modulePath[0]->name : '/';

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $feedback->actions = $this->loadModel('action')->processActionForAPI($data->data->actions, $data->data->users, $this->lang->feedback);
        $this->send(200, $this->format($feedback, 'activatedDate:time,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,mailto:userList,resolvedBy:user,resolvedDate:time,closedBy:user,closedDate:time,lastEditedBy:user,lastEditedDate:time,deadline:date,deleted:bool'));
    }

    /**
     * PUT method.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function put($feedbackID)
    {
        $oldFeedback = $this->loadModel('feedback')->getById($feedbackID);

        $fields = 'module,product,type,title,public,desc,status,feedbackBy,notifyEmail,notify,uid';
        $this->batchSetPost($fields, $oldFeedback);

        $control = $this->loadController('feedback', 'edit');
        $control->edit($feedbackID, '');

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $feedback = $this->feedback->getByID($feedbackID);

        return $this->send(200, $this->format($feedback, 'openedBy:user,openedDate:time,reviewedBy:user,reviewedDate:time,processedBy:user,processedDate:time,closedBy:user,closedDate:time,editedBy:user,editedDate:time,mailto:userList,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function delete($productID)
    {
        $control = $this->loadController('product', 'delete');
        $control->delete($productID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}

