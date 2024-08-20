<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class closeExecutionTester extends tester
{
    /**
     * 关闭执行弹窗中输入实际完成日期。
     * Input fields.
     *
     * @param  string $realEnd
     * @access public
     */
    public function inputFields($realEnd)
    {
        $form = $this->initForm('execution', 'view', array('execution' => '101'), 'appIframe-execution');
        $form->dom->close->click();
        if(isset($realEnd)) $form->dom->realEnd->datePicker($realEnd);
        $form->wait(1);
        $form->dom->closeSubmit->click();
    }
}
