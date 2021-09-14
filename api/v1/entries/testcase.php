<?php
/**
 * The testcase entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class testcaseEntry extends entry 
{
    /**
     * GET method.
     *
     * @param  int    $testcaseID
     * @access public
     * @return void
     */
    public function get($testcaseID)
    {
        $control = $this->loadController('testcase', 'view');
        $control->view($testcaseID, $this->param('version', 0));

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->case)) $this->sendError(400, 'error');

        $case = $data->case;
        $case->steps = (isset($case->steps) and !empty($case->steps)) ? array_values(get_object_vars($case->steps)) : array();

        $this->send(200, $this->format($case, 'openedDate:time,lastEditedDate:time,lastRunDate:time,scriptedDate:date,reviewedDate:date,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $testcaseID
     * @access public
     * @return void
     */
    public function delete($testcaseID)
    {
        $control = $this->loadController('testcase', 'delete');
        $control->delete($testcaseID, 'yes');

        $this->getData();

        $this->sendSuccess(200, 'success');
    }
}
