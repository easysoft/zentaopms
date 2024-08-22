<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class suspendExecutionTester extends tester
{
    /**
     * 挂起执行
     * Suspend execution.
     *
     * @param  string $executionId
     * @access public
     * @return void
     */
    public function suspendExecution($executionId)
    {
        $form = $this->iniForm('execution', 'view', array('execution => $executionId'), 'appIframe-execution');
        $form->dom->suspend->click();
        $form->dom->suspendSubmit->click();
        $form->wait(1);
    }
}
