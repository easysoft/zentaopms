<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class putoffExecutionTester extends tester
{
    /**
     * 输入延期执行表单字段。
     * Input fields.
     *
     * @param  array  $execution
     * @param  string $executionId
     * @access public
     */
    public function inputFields($execution, $executionId)
    {
        $form = $this->initForm('execution', 'view', array('execution' => $executionId ), 'appIframe-execution');
        $status = $form->dom->status->getText();
        $form->dom->putoff->click();
        if(isset($execution['begin'])) $form->dom->begin->datePicker($execution['begin']);
        if(isset($execution['end'])) $form->dom->end->datePicker($execution['end']);
        $form->dom->putoffSubmit->click();
        $form->wait(1);
    }
}
