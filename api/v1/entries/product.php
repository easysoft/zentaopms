<?php
/**
 * 禅道API的product资源类
 * 版本V1
 *
 * The product entry point of zentaopms
 * Version 1
 */
class productEntry extends Entry
{
    public function get($productID)
    {
        $control = $this->loadController('product', 'view');
        $control->view($productID);

        $data    = $this->getData();
        if(isset($data->status) and $data->status == 'success') return $this->send(200, $data->data->product);
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $this->sendError(400, 'error');
    }

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

        $this->sendSuccess(200, 'success');
    }

    public function delete($productID)
    {
        $control = $this->loadController('product', 'delete');
        $control->delete($productID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
