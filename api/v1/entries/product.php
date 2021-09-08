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
class productEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
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
        $this->sendSuccess(200, $this->format($product, 'createdDate:time'));
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
