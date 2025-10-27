<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
        $form->wait(1);

        /* 检查干系人详情页信息。*/
        $viewName = $viewPage->dom->name->getText();
        $viewType = $viewPage->dom->type->getText();
        if($browseName != $viewName) return $this->failed('干系人姓名错误');
        if($browseType != $viewType) return $this->failed('干系人类型错误');
        return $this->success('干系人信息正确');
    }
}
