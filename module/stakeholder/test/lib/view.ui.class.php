<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class viewTester extends tester
{
    /**
     * Check the stakeholder view page information.
     * 检查干系人详情页信息。
     *
     * @access public
     * @return object
     */
    public function view()
    {
        $form = $this->initForm('stakeholder', 'browse', array('project' => 1), 'appIframe-project');
        $browseName = $form->dom->title->getText();
        $browseType = $form->dom->type->getText();
        $form->dom->title->click();
        $viewPage = $this->loadPage('stakeholder', 'view');
    }
}
