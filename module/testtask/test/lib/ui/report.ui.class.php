<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class reportTester extends tester
{
    /**
     * 检查报表数据。
     * Check report data.
     *
     * @param  string $report 报表名称
     * @param  array  $data   报表条目、值、百分比
     * @access public
     * @return void
     */
    public function check($report, $data)
    {
        $form = $this->initForm('testtask', 'report', array('productID' => '1', 'taskID' => '1', 'browseType' => 'all', 'chartType' => 'pie'), 'appIframe-qa');
        $form->wait(2);
        $form->dom->$report->click();
        $form->wait(1);
        $form->dom->createBtn->click();
        $form->wait(1);
        /* 获取报表中条目数 */
        $itemXpath = "//div[@class='tab-pane active']//tbody/tr";
        $itemNum   = count($form->dom->getElementList($itemXpath)->element);
        $types     = array('pie', 'bar', 'line');
        foreach($types as $type)
        {
            $form->dom->$type->click();
            $form->wait(1);
            for($num =2; $num <= $itemNum; $num++)
            {
                $message = $this->checkReport($report, $type, $num, $data);
                if($message == 'titleError')   return $this->failed($type . '报表第' . $num . '行显示名称错误');
                if($message == 'itemError')    return $this->failed($type . '报表第' . $num . '行显示条目错误');
                if($message == 'valueError')   return $this->failed($type . '报表第' . $num . '行显示值错误');
                if($message == 'percentError') return $this->failed($type . '报表第' . $num . '行显示百分比错误');
                $form->wait(1);
            }
        }
        if($message == 'success') return $this->success($report . '报表数据正确');
    }

    /**
     * 检查不同报表数据。
     * Check different report data.
     *
     * @param  string $report 报表名称
     * @param  string $type   报表类型
     * @param  array  $num    查看的报表条目
     * @param  array  $data   报表条目、值、百分比
     * @access public
     * @return void
     */
    public function checkReport($report, $type, $num, $data)
    {
        $form = $this->loadPage();
        $form->wait(2);

        if($form->dom->title->getText() != $this->lang->testtask->report->charts->$report) return 'titleError';
        /* 各行的Xpath */
        $form->dom->xpath['item']    = "//div[@class='tab-pane active']//tbody/tr[$num]/td[1]";
        $form->dom->xpath['value']   = "//div[@class='tab-pane active']//tbody/tr[$num]/td[2]";
        $form->dom->xpath['percent'] = "//div[@class='tab-pane active']//tbody/tr[$num]/td[3]";
        /* 从测试数据中提取对应的子数组，子数组中为此行的条目、值、百分比 */
        $form->wait(1);
        $key = array_search($form->dom->item->getText(), array_column($data, $this->config->default->lang));
        if($key === false) return 'itemError';
        $result = $data[$key];

        if($form->dom->value->getText() != $result['value'])     return 'valueError';
        if($form->dom->percent->getText() != $result['percent']) return 'percentError';
        return 'success';
    }
}
