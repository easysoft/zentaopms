<?php
/**
 * The products entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class productsEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function get()
    {
        $programID = $this->param('program', 0);
        if($programID)
        {
            $control = $this->loadController('program', 'product');
            $control->product($programID, $this->param('status', 'all'), $this->param('order', 'order_asc'), 0, 10000);

            /* Response */
            $data = $this->getData();
            if(isset($data->status) and $data->status == 'success')
            {
                $result   = array();
                $products = $data->data->products;
                foreach($products as $product) $result[] = $this->format($product, 'createdDate:time');

                return $this->send(200, array('products' => $result));
            }
        }
        else
        {
            $control = $this->loadController('product', 'all');
            $control->all($this->param('status', 'all'), $this->param('order', 'order_asc'));

            /* Response */
            $data = $this->getData();
            if(isset($data->status) and $data->status == 'success')
            {
                $result   = array();
                $products = $data->data->productStats;
                foreach($products as $product) $result[] = $this->format($product, 'createdDate:time');

                return $this->send(200, array('products' => $result));
            }
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @access public
     * @return void
     */
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

        /* Response */
        $product = $this->loadModel('product')->getByID($data->id);
        $product = $this->format($product, 'createdDate:time,whitelist:[]string');

        $this->send(200, $product);
    }
}
