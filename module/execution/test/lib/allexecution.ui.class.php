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
}
