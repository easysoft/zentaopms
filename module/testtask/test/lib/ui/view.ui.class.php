<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class viewTester extends tester
{
    /**
     * 检查测试单概况页面。
     * Check view page of testtask.
     *
     * @access public
     * @return void
     */
    public function check()
    {
        $form = $this->initForm('testtask', 'browse', array('productID' => 1), 'appIframe-qa');
        $id   = $form->dom->firstID->getText();
        $name = $form->dom->firstName->getText();
        /* 测试单列表点击测试单名称，进入测试单用例列表页 */
        $form->dom->firstName->click();
        $form->wait(1);
        /* 测试单用例列表页点击概况按钮 */
        $caseForm = $this->loadPage('testtask', 'cases');
        $caseForm->dom->xpath['viewXpath'] = "//span[text()='{$this->lang->testtask->view}']/..";
        $caseForm->dom->viewXpath->click();
        $caseForm->wait(1);
        /* 检查测试单概况页面 */
        $viewForm = $this->loadPage('testtask', 'view');
        $viewForm->wait(1);
        if($viewForm->dom->id->getText() != $id)     return $this->failed('测试单ID错误');
        if($viewForm->dom->name->getText() != $name) return $this->failed('测试单名称错误');
        return $this->success('测试单概况页面检查成功');
    }
}
