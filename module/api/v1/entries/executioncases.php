<?php
/**
 * The execution cases entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class executionCasesEntry extends entry
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
        if(!$executionID) $executionID   = $this->param('execution', 0);
        if(empty($executionID)) return $this->sendError(400, 'Need execution id.');

        $control = $this->loadController('execution', 'testcase');
        $control->testcase($executionID, $this->param('product', 0), $this->param('branch', 0), $this->param('status', 'all'), $this->param('module', 0), $this->param('order', 'id_desc'), 0, $this->param('limit', 20), $this->param('page', 1));

        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $cases  = $data->data->cases;
            $pager  = $data->data->pager;
            $result = array();
            foreach($cases as $case)
            {
                $case->statusName = $this->lang->testcase->statusList[$case->status];
                $result[] = $this->format($case, 'openedDate:time,reviewedDate:date,lastEditedDate:time,lastRunDate:time');
            }

            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'cases' => $result));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        return $this->sendError(400, 'error');
    }
}
