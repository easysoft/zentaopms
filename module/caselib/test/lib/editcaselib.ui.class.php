<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
        $form->dom->btn($this->lang->caselib->view)->click();
        $form->dom->editBtn->click();
        if(isset($caselib['name'])) $form->dom->name->setValue($caselib['name']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        /* 检查创建用例库页面必填提示信息 */
        if(is_object($form->dom->nameTip))
        {
            $nameTipForm = $form->dom->nameTip->getText();
            $nameTip     = sprintf($this->lang->error->notempty, $this->lang->caselib->name);
            return ($nameTipForm == $nameTip) ? $this->success('编辑用例库表单页必填提示信息正确') : $this->failed('编辑用例库表单页必填提示信息不正确');
        }

        /* 点击查看库概况 */
        $browsePage = $this->loadPage('caselib', 'browse');
        $browsePage->dom->btn($this->lang->caselib->view)->click();
        $browsePage->wait(1);

        //断言检查用例库名称是否正确
        if($browsePage->dom->viewName->getText() == $caselib['name']) return $this->success('用例库编辑成功');
        return $this->failed('用例库编辑失败');
    }
}
