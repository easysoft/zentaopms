<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class cancelTaskTester extends tester
{
    /**
     * 取消任务。
     * Cancel task.
     *
     * @param  string $id
     * @param  string $status
     * @access public
     * @return object
     */
    public function cancel($id, $status)
    {
        $form = $this->initForm('task', 'view', array('taskID' => $id), 'appIframe-execution');
        $form->dom->xpath['cancelBtn']  = "//a[@title='{$this->lang->task->cancel}']";
        if(in_array($status, array('done', 'cancel', 'closed')))
        {
            if(is_object($form->dom->cancelBtn)) return $this->failed('错误的显示了取消按钮');
            return $this->success('没有显示取消按钮');
        }
        $form->dom->cancelBtn->click();
        $form->wait(1);
        $form->dom->submitBtn->click();
        $form->wait(2);
        if($form->dom->taskStatus->getText() != $this->lang->task->statusList->cancel) return $this->failed('取消任务后状态错误');
        return $this->success('取消任务成功');
    }
}
