<?php
/**
 * 禅道API的testcases资源类
 * 版本V1
 *
 * The testcases entry point of zentaopms
 * Version 1
 */
class testcasesEntry extends entry
{
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
