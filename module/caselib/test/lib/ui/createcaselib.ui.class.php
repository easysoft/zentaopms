<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createCaselibTester extends tester
{
    /**
     * 创建用例库
     * Create caselib.
     *
     * @param  array $caselib
     * @param  int   $libID
     * @access public
     */
    public function createCaselib($caselib, $libID)
    {
        if($libID)
        {
            $form = $this->initForm('caselib', 'create', array('libID' => '1'), 'appIframe-qa');
            $form->dom->btn($this->lang->caselib->create)->click();
        }
        else
        {
            $form = $this->initForm('caselib', 'create', '', 'appIframe-qa');
        }

        if(isset($caselib['name'])) $form->dom->name->setValue($caselib['name']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        /* 检查创建用例库页面必填提示信息 */
        if(is_object($form->dom->nameTip))
        {
            $nameTipForm = $form->dom->nameTip->getText();
            $nameTip     = sprintf($this->lang->error->notempty, $this->lang->caselib->name);
            return ($nameTipForm == $nameTip) ? $this->success('创建用例库表单页必填提示信息正确') : $this->failed('创建用例库表单页必填提示信息不正确');
        }

        /* 点击查看库概况 */
        $browsePage = $this->loadPage('caselib', 'browse');
        $browsePage->dom->btn($this->lang->caselib->view)->click();
        $browsePage->wait(1);

        //断言检查用例库名称是否正确
        if($browsePage->dom->viewName->getText() == $caselib['name']) return $this->success('用例库创建成功');
        return $this->failed('用例库创建失败');
    }
}
