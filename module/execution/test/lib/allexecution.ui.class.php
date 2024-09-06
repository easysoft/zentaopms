<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class allExecutionTester extends tester
{
    /**
     * 检查Tab标签下的数据。
     * Check the data of the Tab tag.
     *
     * @param  string $tab       all|undone|wait|doing|suspended|closed
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('execution', 'all', '', 'appIframe-execution');
        $selectedTab = $tab . 'Tab';
        $form->dom->$selectedTab->click();
        $form->wait(1);
        if($form->dom->num->getText() == $expectNum) return $this->success($tab . '标签下显示条数正确');
        return $this->failed($tab . '标签下显示条数不正确');
    }

    /**
     * 批量操作执行状态。
     * Batch operation execution status.
     *
     * @param  string $status wait|doing|suspended|closed
     * @access public
     * @return object
     */
    public function changeStatus($status)
    {
        $form = $this->initForm('execution', 'all', '', 'appIframe-execution');
        $firstId = $form->dom->firstId->getText();
        $form->dom->firstCheckbox->click();
        $form->dom->statusBtn->click();
        $form->wait(1);
        $form->dom->$status->click();

        $viewForm = $this->initForm('execution', 'view', array('execution' => $firstId), 'appIframe-execution');
        if($viewForm->dom->status->getText() == $this->lang->execution->statusList->$status) return $this->success("批量操作执行状态为{$status}成功");
        return $this->failed("批量操作执行状态为{$status}失败");
    }
}
