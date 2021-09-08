<?php
/**
 * The testcases entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class testcasesEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return void
     */
    public function get()
    {
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));

        $testcases = $this->loadModel('testcase')->getByStatus($this->param('productID', 0), $this->param('branch', ''), $this->param('type', 'all'), $this->param('status', 'all'), $this->param('moduleID', 0), $this->param('order', 'id_desc'), $pager, $this->param('auto', 'no'));

        $result = array();
        foreach($testcases as $testcase)
        {
            $result[] = $this->format($testcase, 'openedDate:time,lastEditedDate:time,lastRunDate:time');
        }

        return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'testcases' => $result));
    }
}
