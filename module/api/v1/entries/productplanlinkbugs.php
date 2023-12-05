<?php
/**
 * The productplanlinkbugs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class productplanLinkBugsEntry extends entry
{
    /**
     * POST method.
     *
     * @param  int    $planID
     * @access public
     * @return string
     */
    public function post($planID)
    {
        $fields = 'bugs';
        $this->batchSetPost($fields);

        $control = $this->loadController('productplan', 'linkBug');
        $control->linkBug($planID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'success')
        {
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

        $this->sendError(400, array('message' => isset($data->message) ? $data->message : 'error'));
    }
}
