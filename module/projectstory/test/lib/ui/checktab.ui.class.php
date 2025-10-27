<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class checkTabTester extends tester
{
    /**
     * 检查项目需求Tab标签下的数据。
     * Check the data of the Tab.
     *
     * @param  string $tab
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('projectstory', 'story', array('project' => '1'), 'appIframe-project');
        $tabs = array('allTab', 'unclosedTab', 'draftTab', 'reviewingTab', 'changingTab');
        if(!in_array($tab, $tabs)) $form->dom->moreTab->click();
        $form->dom->$tab->click();
        $form->wait(2);
        if($form->dom->num->getText() == $expectNum) return $this->success($tab . '下显示条数正确');
        return $this->failed($tab . '下显示条数不正确');
    }
}
