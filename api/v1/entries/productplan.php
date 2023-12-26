<?php
/**
 * The productplan entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class productplanEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $planID
     * @access public
     * @return string
     */
    public function get($planID)
    {
        $fields = $this->param('fields');

        $control = $this->loadController('productplan', 'view');
        $control->view($planID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $plan = $data->data->plan;
        $plan->stories = $data->data->planStories;
        $plan->bugs    = $data->data->planBugs;

        $plan = $this->format($plan, 'begin:date,end:date,deleted:bool,stories:array,bugs:array');

        return $this->send(200, $plan);
    }

    /**
     * PUT method.
     *
     * @param  int    $planID
     * @access public
     * @return string
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

        /* Get plan info. */
        $control = $this->loadController('productplan', 'view');
        $control->view($planID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $plan = $data->data->plan;
        $plan->stories = $data->data->planStories;
        $plan->bugs    = $data->data->planBugs;

        return $this->send(200, $this->format($plan, 'begin:date,end:date,deleted:bool,stories:array,bugs:array'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function delete($planID)
    {
        $control = $this->loadController('productplan', 'delete');
        $control->delete($planID, 'yes');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}
