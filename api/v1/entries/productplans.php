<?php
/**
 * The productplans entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class productplansEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function get($productID = 0)
    {
        if(!$productID) $productID = $this->param('product', 0);
        if(!$productID) return $this->sendError(400, 'No product id.');

        $control = $this->loadController('productplan', 'browse');
        $control->browse($productID, $this->param('branch', 0), $this->param('status', 'all'), $this->param('order', 'begin_desc'), 0, $this->param('limit', 20), $this->param('page', 1));

        /* Response */
        $data = $this->getData();
        if(isset($data->status) and $data->status == 'success')
        {
            $result = array();
            $plans  = $data->data->plans;
            foreach($plans as $plan) $result[] = $plan;

            return $this->send(200, array('plans' => $result));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        return $this->sendError(400, 'error');
    }
}
