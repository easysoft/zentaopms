<?php
/**
 * The execution builds entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class executionBuildsEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function get($executionID = 0)
    {
        if(empty($executionID)) $executionID = $this->param('execution', 0);
        if(empty($executionID)) return $this->sendError(400, "Need execution id.");

        $control = $this->loadController('execution', 'build');
        $control->build($executionID, $this->param('status', 'all'), $this->param('param', 0), $this->param('order', 't1.date_desc,t1.id_desc'));
        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $result = array();
        foreach($data->data->executionBuilds as $builds)
        {
            foreach($builds as $build) $result[] = $this->format($build, 'builder:user,bugs:idList,stories:idList,deleted:bool');
        }

        return $this->send(200, array('total' => count($result), 'builds' => $result));
    }
}
