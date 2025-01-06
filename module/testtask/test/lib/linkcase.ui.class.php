<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class linkcaseTester extends tester
{
    /**
     * 测试单关联用例。
     * Testtask link case.
     *
     * @param  int $num
     * @access public
     * @return void
     */
    public function linkCase($num)
    {
        $form = $this->initForm('testtask', 'linkCase', array('taskID' => '1'), 'appIframe-qa');
        if($form->dom->num->getText() != $num) return $this->failed('可以被测试单关联的用例的数量错误');
        $form->dom->checkbox->click();
        $form->wait(1);
        $form->dom->saveBtn->click();
    }
}
