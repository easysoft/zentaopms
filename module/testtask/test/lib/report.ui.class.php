<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class reportTester extends tester
{
    /**
     * 检查报表数据。
     * Check report data.
     *
     * @param  string $report 报表名称
     * @param  string $type   报表类型
     * @param  array  $data   报表条目、值、百分比
     * @access public
     * @return void
     */
    public function checkReport($report, $type, $data)
    {
        $form = $this->initForm('testtask', 'report', array('productID' => '1', 'taskID' => '1', 'browseType' => 'all', 'chartType' => $type), 'appIframe-qa');
        $form->dom->$report->click();
        $form->wait(1);
        $form->dom->createBtn->click();
        $form->wait(1);
        if($form->dom->title->getText() != $this->lang->testtask->report->charts->$report) return $this->failed('显示报表错误');
    }
}
