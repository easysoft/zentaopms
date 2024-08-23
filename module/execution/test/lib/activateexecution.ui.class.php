<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
Class activateExecutionTester extends tester
{
    /**
     * 输入激活执行表单字段。
     * Input fields.
     *
     * @param string $end
     * @param string $executionId
     * @access public
     */
    public function inputFields($end, $executionId)
    {
        $form = $this->initForm('execution', 'view', array('execution' => $executionId ), 'appIframe-execution');
        $form->dom->btn($this->lang->execution->activate)->click();
        if(isset($end)) $form->dom->end->datePicker($end);
        $form->dom->activateSubmit->click();
    }
}
