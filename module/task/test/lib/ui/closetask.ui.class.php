<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class closeTaskTester extends tester
{
    /**
     * 关闭任务。
     * Close task.
     *
     * @param  string $id
     * @param  string $status
     * @access public
     * @return object
     */
    public function close($id, $status)
    {
        $form = $this->initForm('task', 'view', array('taskID' => $id), 'appIframe-execution');
        $form->dom->xpath['closeBtn']   = "//a[@title='{$this->lang->task->close}']";
        if(!in_array($status, array('done', 'cancel')))
        {
            if(is_object($form->dom->closeBtn)) return $this->failed('错误的显示了关闭按钮');
            return $this->success('没有显示关闭按钮');
        }
        $form->dom->closeBtn->click();
        $form->wait(1);
        $form->dom->submitBtn->click();
        $form->wait(2);
        if($form->dom->taskStatus->getText() != $this->lang->task->statusList->closed) return $this->failed('关闭任务后状态错误');
        return $this->success('关闭任务成功');
    }
}
