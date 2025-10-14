<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class pauseTaskTester extends tester
{
    /**
     * 暂停任务。
     * Pause task.
     *
     * @param  string $id
     * @param  string $status
     * @access public
     * @return object
     */
    public function pause($id, $status)
    {
        $form = $this->initForm('task', 'view', array('taskID' => $id), 'appIframe-execution');
        $form->dom->xpath['pauseBtn']   = "//a[@title='{$this->lang->task->pause}']";
        if($status != 'doing')
        {
            if(is_object($form->dom->pauseBtn)) return $this->failed('错误的显示了暂停按钮');
            return $this->success('没有显示暂停按钮');
        }
        $form->dom->pauseBtn->click();
        $form->wait(1);
        $form->dom->submitBtn->click();
        $form->wait(3);
        if($form->dom->taskStatus->getText() != $this->lang->task->statusList->pause) return $this->failed('暂停任务后状态错误');
        return $this->success('暂停任务成功');
    }
}
