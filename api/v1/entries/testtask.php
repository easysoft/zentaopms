<?php
/**
 * The testtask entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class testtaskEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $testtaskID
     * @access public
     * @return void
     */
    public function get($testtaskID)
    {
        $control = $this->loadController('testtask', 'cases');
        $control->cases($testtaskID, 'all', 0, $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        if(!isset($data->data->task)) $this->sendError(400, 'error');

        $testtask = $data->data->task;
        $testtask->testcases = array();
        foreach($data->data->runs as $run)
        {
            $testtask->testcases[] = $this->format($run, 'openedBy:user,openedDate:time,reviewedBy:user,reviewedDate:date,lastEditedBy:user,lastEditedDate:time');
        }

        $this->send(200, $this->format($testtask, 'begin:date,end:date,mailto:userList,owner:user,realFinishedDate:time'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $testtaskID
     * @access public
     * @return void
     */
    public function delete($testtaskID)
    {
        $control = $this->loadController('testtask', 'delete');
        $control->delete($testtaskID, 'yes');

        $this->getData();

        $this->sendSuccess(200, 'success');
    }
}
