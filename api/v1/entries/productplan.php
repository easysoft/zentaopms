<?php
/**
 * The productplan entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class productplanEntry extends Entry
{
    /**
     * GET method.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function get($planID)
    {
        $fields = $this->param('fields');

        $control = $this->loadController('productplan', 'view');
        $control->view($planID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail')
        {
            return isset($data->code) and $data->code == 404 ? $this->send404() : $this->sendError(400, $data->message);
        }

        $plan = $this->format($data->data->plan, 'begin:date,end:date,deleted:bool');

        return $this->send(200, $plan);
    }

    /**
     * PUT method.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function put($planID)
    {
        $oldPlan = $this->loadModel('productplan')->getByID($planID);

        /* Set $_POST variables. */
        $fields = 'title,begin,end,desc';
        $this->batchSetPost($fields, $oldPlan);

        $control = $this->loadController('productplan', 'edit');
        $control->edit($planID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $plan = $this->productplan->getByID($planID);
        $this->sendSuccess(200, $this->format($plan, 'begin:date,end:date'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function delete($planID)
    {
        $control = $this->loadController('productplan', 'delete');
        $control->delete($planID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
