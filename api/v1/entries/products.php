<?php
/**
 * 禅道API的products资源类
 * 版本V1
 *
 * The products entry point of zentaopms
 * Version 1
 */
class productsEntry extends entry
{
    public function get()
    {
        $control = $this->loadController('product', 'all');
        $control->all();
        $data  = $this->getData();
        $pager = $data->data->pager;

        if(isset($data->status) and $data->status == 'success')
        {
            return $this->send(200, array('products' => $data->data->productStats));
        }
        if(isset($data->status) and $data->status == 'fail')
        {
            return $this->sendError(400, $data->message);
        }

        return $this->sendError(400, 'error');
    }

    public function post()
    {
        $fields = 'program,line,name,PO,QD,RD,type,desc,whitelist';
        $this->batchSetPost($fields);

        $this->setPost('acl', $this->request('acl', 'private'));
        $this->setPost('whitelist', $this->request('whitelist', array()));

        $control = $this->loadController('product', 'create');
        $this->requireFields('name,program');

        $control->create($this->request('program'));

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $product = $this->loadModel('product')->getByID($data->id);

        $this->send(200, $product);
    }
}
