<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class groupCaseTester extends tester
{
    /**
     * 检查分组视图下用例数量。
     * Check the number of cases in the group view.
     *
     * @param  string $tab
     * @param  string $story
     * @param  string $num
     * @access public
     * @return void
     */
    public function checkNum($story, $num, $tab = 'all')
    {
        $form = $this->initForm('testtask', 'groupCase', array('taskID' => '1', 'browseType' => $tab), 'appIframe-qa');
        $caseXpath = "//div[@data-col='storyTitle']/div[text()='{$story}']";
        $form->wait(1);
        if(count($form->dom->getElementList($caseXpath)->element) == $num) return $this->success('用例数量正确');
        return $this->failed('用例数量不正确');
    }
}
