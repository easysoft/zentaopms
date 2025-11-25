<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class testtaskTester extends tester
{
    /**
     * 检查测试单列表统计数据。
     * Check the data of testtask list.
     *
     * @param  string $select
     * @param  array  $number
     * @access public
     * @return object
     */
    public function checkNum($select, $number)
    {
        $form = $this->initForm('project', 'testtask', array('project' => 1), 'appIframe-project');
        if($select) $form->dom->totalCheckbox->click();
        $form->wait(1);

        if($form->dom->total->getText()   != $number[0]) return $this->failed('测试单总数统计错误');
        if($form->dom->waiting->getText() != $number[1]) return $this->failed('待测试的测试单数据统计错误');
        if($form->dom->doing->getText()   != $number[2]) return $this->failed('测试中的测试单数据统计错误');
        if($form->dom->blocked->getText() != $number[3]) return $this->failed('被阻塞的测试单数据统计错误');
        if($form->dom->done->getText()    != $number[4]) return $this->failed('已测试的测试单数据统计错误');
        return $this->success('测试单列表统计数据正确');
    }

    /**
     * 不同产品测试单批量生成测试报告。
     * Create report for different products.
     *
     * @access public
     * @return void
     */
    public function createReport()
    {
        $form = $this->initForm('project', 'testtask', array('project' => 1), 'appIframe-project');
        $form->dom->totalCheckbox->click();
        $form->dom->reportBtn->click();
        $form->wait(3);

        if($form->dom->alertModal('text') == $this->lang->testreport->moreProduct) return $this->success('不同产品测试单生成测试报告提示信息正确');
        return $this->failed('不同产品测试单生成测试报告提示信息错误');
    }
}
