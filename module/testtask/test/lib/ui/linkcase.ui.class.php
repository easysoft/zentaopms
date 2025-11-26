<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class linkCaseTester extends tester
{
    /**
     * 测试单关联用例。
     * Testtask link case.
     *
     * @param  int    $num
     * @access public
     * @return void
     */
    public function linkCase($num)
    {
        $casesForm = $this->initForm('testtask', 'cases', array('taskID' => '1'), 'appIframe-qa');
        $allNum    = intval($casesForm->dom->allCasesNum->getText());
        $form      = $this->initForm('testtask', 'linkCase', array('taskID' => '1'), 'appIframe-qa');
        if($form->dom->num->getText() != $num) return $this->failed('可以被测试单关联的用例的数量错误');
        $form->dom->checkbox->click();
        $form->wait(1);
        $form->dom->saveBtn->click();
        $form->wait(2);

        $casesForm = $this->loadPage('testtask', 'cases', array('taskID' => '1'), 'appIframe-qa');
        if(intval($casesForm->dom->allCasesNum->getText()) == $allNum + 1) return $this->success('测试单关联用例成功');
        return $this->failed('测试单关联用例失败');
    }
}
