<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class editCaselibTester extends tester
{
    /**
     * 编辑用例库
     * Edit caselib.
     *
     * @param  array $caselib
     * @access public
     */
    public function editCaselib($caselib)
    {
        $form = $this->initForm('caselib', 'browse', array('libID' => 1), 'appIframe-qa');
        $form->wait(1);
        $form->dom->viewBtn->click();
        $form->dom->editBtn->click();
        if(isset($caselib['name'])) $form->dom->name->setValue($caselib['name']);

        $form->dom->saveBtn->click();
        $form->wait(1);

        /* 检查创建用例库页面必填提示信息 */
        if(is_object($form->dom->nameTip))
        {
            $nameTipForm = $form->dom->nameTip->getText();
            return ($nameTipForm == '『名称』不能为空。') ? $this->success('编辑用例库表单页必填提示信息正确') : $this->failed('编辑用例库表单页必填提示信息不正确');
        }

        /* 点击查看库概况 */
        $browsePage = $this->loadPage('caselib', 'browse');
        $browsePage->dom->viewBtn->click();
        $browsePage->wait(1);

        //断言检查用例库名称是否正确
        if($browsePage->dom->viewName->getText() == $caselib['name']) return $this->success('用例库编辑成功');
        return $this->failed('用例库编辑失败');
    }
}
