<?php
/**
 * The testsuite entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class testsuiteEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $testsuiteID
     * @access public
     * @return string
     */
    public function get($testsuiteID)
    {
        $control = $this->loadController('testsuite', 'view');
        $control->view($testsuiteID, $this->param('order', 'id_desc'), 0, $this->param('limit', 20), $this->param('page', 1));

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        if(!isset($data->data->suite)) $this->sendError(400, 'error');

        $suite = $this->format($data->data->suite, 'addedBy:user,addedDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool');
        $suite->testcases = array();

        foreach($data->data->cases as $case)
        {
            $suite->testcases[] = $this->format($case, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,lastRunDate:time,scriptedDate:date,reviewedBy:user,reviewedDate:date,deleted:bool');
        }

        return $this->send(200, $suite);
    }

    /**
     * DELETE method.
     *
     * @param  int    $testsuiteID
     * @access public
     * @return string
     */
    public function delete($testsuiteID)
    {
        $control = $this->loadController('testsuite', 'delete');
        $control->delete($testsuiteID, 'yes');

        $this->getData();

        return $this->sendSuccess(200, 'success');
    }
}
