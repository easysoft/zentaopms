<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class testtaskTester extends tester
{
    /**
     * 检查统计数据。
     * Check statistics.
     *
     * @param  bool   $select
     * @param  array  $nums
     * @access public
     * @return void
     */
    public function checkNum($select, $nums)
    {
        $form = $this->initForm('execution', 'testtask', array('execution' => '2' ), 'appIframe-execution');
        if($select) $form->dom->totalCheckbox->click();
        $form->wait(1);

        if($form->dom->total->getText() != $nums[0])   return $this->failed('测试单总数统计错误');
        if($form->dom->waiting->getText() != $nums[1]) return $this->failed('待测试的测试单数据统计错误');
        if($form->dom->doing->getText() != $nums[2])   return $this->failed('测试中的测试单数据统计错误');
        if($form->dom->blocked->getText() != $nums[3]) return $this->failed('被阻塞的测试单数据统计错误');
        if($form->dom->done->getText() != $nums[4])    return $this->failed('已测试的测试单数据统计错误');
        return $this->success('测试单统计数据正确');
    }

    /**
     * 不同产品的测试单生成测试报告。
     * Create report for different products.
     *
     * @access public
     * @return void
     */
    public function createReport()
    {
        $form = $this->initForm('execution', 'testtask', array('execution' => '2' ), 'appIframe-execution');
        $form->dom->totalCheckbox->click();
        $form->wait(1);
        $form->dom->report->click();
        $form->wait(2);

        if($form->dom->modalInfo->getText() == $this->lang->testreport->moreProduct) return $this->success('不同产品的测试单生成测试报告提示正确');
        return $this->failed('不同产品的测试单生成测试报告提示错误');
    }
}
