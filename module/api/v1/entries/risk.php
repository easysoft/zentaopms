<?php
/**
 * The risk entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class riskEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $riskID
     * @access public
     * @return string
     */
    public function get($riskID)
    {
        $control = $this->loadController('risk', 'view');
        $control->view($riskID);

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->status) and $data->status == 'success') return $this->send(200, $this->format($data->data->risk, 'createdDate:time,editedDate:time'));
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $this->sendError(400, 'error');
    }

    /**
     * PUT method.
     *
     * @param  int    $riskID
     * @access public
     * @return string
     */
    public function put($riskID)
    {
        $oldRisk = $this->loadModel('risk')->getByID($riskID);
        if(!$oldRisk) return $this->send404();

        /* Set $_POST variables. */
        $fields = 'source,name,category,strategy,status,impact,probability,rate,identifiedDate,plannedClosedDate,actualClosedDate,resolvedBy,assignedTo,prevention,remedy,resolution';
        $this->batchSetPost($fields, $oldRisk);

        $control = $this->loadController('risk', 'edit');
        $control->edit($riskID);

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $risk = $this->risk->getByID($riskID);
        return $this->send(200, $this->format($risk, 'createdDate:time,editedDate:time'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $riskID
     * @access public
     * @return string
     */
    public function delete($riskID)
    {
        $control = $this->loadController('risk', 'delete');
        $control->delete($riskID, 'true');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}
