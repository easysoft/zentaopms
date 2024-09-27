<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTabTester extends tester
{
    /**
     * 项目列表tab标签数量。
     * Project browse tab.
     *
     * @param  string $tab
     * @param  string $expectNum
     * @access public
     */
    public function checkBrowseTab($tab, $expectNum)
    {
        $form = $this->initForm('project', 'browse', '', 'appIframe-project');
        $tabs = array('all', 'undone', 'wait', 'doing');
        if(!in_array($tab, $tabs)) $form->dom->moreTab->click();
        $form->wait(2);
        $form->dom->$tab->click();
        $form->wait(2);
        /*添加断言，判断标签下条数是否符合预期*/
        if($form->dom->num->getText() == $expectNum) return $this->success($tab . '标签下条数显示正确');
        return $this->failed($tab . '标签下条数显示不正确');
    }
}
