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

        $feedback->productName  = $data->data->product;
        $feedback->moduleName   = $data->data->modulePath[0]->name ? $data->data->modulePath[0]->name : '/';
        $feedback->resultType   = $data->data->type;
        if($feedback->resultInfo->deleted == 0) $feedback->resultStatus = $this->loadModel('feedback')->processStatus($feedback->resultType, $feedback->resultInfo);

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $feedback->actions = $this->loadModel('action')->processActionForAPI($data->data->actions, $data->data->users, $this->lang->feedback);
        $this->send(200, $this->format($feedback, 'activatedDate:time,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,mailto:userList,resolvedBy:user,resolvedDate:time,closedBy:user,closedDate:time,lastEditedBy:user,lastEditedDate:time,deadline:date,deleted:bool'));
    }

    /**
     * PUT method.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function put($productID)
    {
        $oldProduct = $this->loadModel('product')->getByID($productID);

        /* Set $_POST variables. */
        $fields = 'program,line,name,PO,QD,RD,type,desc,whitelist,status,acl';
        $this->batchSetPost($fields, $oldProduct);

        $control = $this->loadController('product', 'edit');
        $control->edit($productID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $product = $this->product->getByID($productID);
        $this->send(200, $this->format($product, 'createdDate:time'));
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

