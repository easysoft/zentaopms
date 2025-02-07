<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class reportTester extends tester
{
    /**
     * 检查报表数据。
     * Check report data.
     *
     * @param  string $report  报表名称
     * @param  string $type    报表类型
     * @param  array  $itemNum 查看的报表条目
     * @param  array  $data    报表条目、值、百分比
     * @access public
     * @return void
     */
    public function checkReport($report, $type, $itemNum = 'a', $data)
    {
        $form = $this->initForm('testtask', 'report', array('productID' => '1', 'taskID' => '1', 'browseType' => 'all', 'chartType' => 'pie'), 'appIframe-qa');
        $form->dom->$report->click();
        $form->wait(1);
        $form->dom->createBtn->click();
        $form->wait(1);
        $form->dom->$type->click();
        $form->wait(1);
        if($form->dom->title->getText() != $this->lang->testtask->report->charts->$report) return $this->failed('显示报表错误');

        $item    = 'item' . $itemNum;
        $value   = 'value' . $itemNum;
        $percent = 'percent' . $itemNum;
        /* 从测试数据中提取对应的子数组，子数组中为此行的条目、值、百分比 */
        $key = array_search($form->dom->$item->getText(), array_column($data, $this->config->default->lang));
        if($key === false) return $this->failed('报表中无此行数据');
        $result = $data[$key];

        if($form->dom->$value->getText() != $result['value'])     return $this->failed('显示报表值错误');
        if($form->dom->$percent->getText() != $result['percent']) return $this->failed('显示报表百分比错误');
        return $this->success('报表数据正确');
    }
}
