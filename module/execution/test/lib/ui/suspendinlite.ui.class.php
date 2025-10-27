<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class suspendExecutionTester extends tester
{
    /**
     * 运营界面挂起看板。
     * Suspend execution.
     *
     * @param  string $executionId
     * @access public
     * @return void
     */
    public function suspend($executionId)
    {
        $currentVision = $this->page->getCookie('vision');
        if(!isset($currentVision) || $currentVision != 'lite') $this->switchVision('lite');
        $form = $this->initForm('execution', 'kanban', array('execution' => $executionId ), 'appIframe-project');
        $form->wait(1);
        $form->dom->kanbanSettingInLite->click();
        $form->wait(1);
        $form->dom->btn($this->lang->execution->suspend)->click();
        $form->dom->suspendSubmitInLite->click();
        $form->wait(3);

        /* 调用view方法，方便获取数据 */
        $viewForm = $this->initForm('execution', 'view', array('execution' => $executionId ), 'appIframe-project');
        if($viewForm->dom->status->getText() != $this->lang->execution->statusList->suspended) return $this->failed('看板状态错误');
        return $this->success('挂起看板成功');
    }
}
