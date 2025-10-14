<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
    public function suspend($executionId)
    {
        $form = $this->initForm('execution', 'view', array('execution' => $executionId ), 'appIframe-execution');
        $form->dom->btn($this->lang->execution->suspend)->click();
        $form->dom->suspendSubmit->click();
        $form->wait(3);

        if($form->dom->status->getText() != $this->lang->execution->statusList->suspended) return $this->failed('执行状态错误');
        return $this->success('挂起执行成功');
    }
}
