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
        $fields = $this->param('fields');

        $control = $this->loadController('product', 'view');
        $control->view($productID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail')
        {
            return isset($data->code) and $data->code == 404 ? $this->send404() : $this->sendError(400, $data->message);
        }

        $product = $this->format($data->data->product, 'createdDate:time');
        if(!$fields) return $this->send(200, $product);

        /* Set other fields. */
        $fields = explode(',', $fields);
        foreach($fields as $field)
        {
            switch($field)
            {
                case 'modules':
                    $control = $this->loadController('tree', 'browse');
                    $control->browse($productID, 'story');
                    $data = $this->getData();
                    if(isset($data->status) and $data->status == 'success')
                    {
                        $product->modules = $data->data->tree;
                    }
                    break;
            }
        }

        return $this->send(200, $product);
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

        $product = $this->product->getByID($productID);
        $this->sendSuccess(200, $this->format($product, 'createdDate:time'));
    }

    public function delete($productID)
    {
        $control = $this->loadController('product', 'delete');
        $control->delete($productID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
