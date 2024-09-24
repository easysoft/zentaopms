<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class bugTester extends tester
{
    /**
     * 检查Tab标签下的数据。
     * Check the data of the Tab tag.
     *
     * @param  string $tab       allTab|unclosedTab|draftTab|reviewingTab
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('execution', 'story', array('execution' => '2'), 'appIframe-execution');
        $form->dom->$tab->click();
        $form->wait(1);
        if($form->dom->num->getText() == $expectNum) return $this->success($tab . '下显示条数正确');
        return $this->failed($tab . '下显示条数不正确');
    }
}
