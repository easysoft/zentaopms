<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class finishTaskTester extends tester
{
    /**
     * 完成任务。
     * Finish task.
     *
     * @param  string $id
     * @param  string $consumed
     * @param  string $assignedTo
     * @param  string $status
     * @access public
     * @return object
     */
    public function finish($id, $consumed, $assignedTo, $status)
    {
        $form = $this->initForm('task', 'view', array('taskID' => $id), 'appIframe-execution');

        $form->dom->xpath['taskAssignedTo'] = "//*[@title='{$this->lang->task->assignedTo}']/../div[2]";
        $form->dom->xpath['taskConsumed']   = "//*[@title='{$this->lang->task->consumed}']/../div[2]";
        $form->dom->xpath['taskLeft']       = "//*[@title='{$this->lang->task->left}']/../div[2]";
        $form->dom->xpath['finishBtn']      = "//a[@title='{$this->lang->task->finish}']";

        $taskConsumed = $form->dom->taskConsumed->getText();

        if(!in_array($status, array('wait', 'doing', 'pause')))
        {
            if(is_object($form->dom->finishBtn)) return $this->failed('错误的显示了完成按钮');
            return $this->success('没有显示完成按钮');
        }

        $form->dom->finishBtn->click();
        $form->wait(1);
        /* 输入完成任务表单内容 */
        $form->dom->currentConsumed->setValue($consumed);
        if(empty($assignedTo))  $form->dom->assignedToDelBtn->click();
        if(!empty($assignedTo)) $form->dom->assignedTo->picker($assignedTo);
        $form->dom->submitBtn->click();
        $form->wait(1);

        if($consumed == '0' || empty($consumed))
        {
            if($status == 'wait')
            {
                if(!is_object($form->dom->currentConsumedTip)) return $this->failed('任务本次消耗为0时没有提示');
                if($form->dom->currentConsumedTip->getText() != $this->lang->task->error->consumedEmpty) return $this->failed('任务本次消耗为0时提示错误');
                return $this->success('任务本次消耗为0时提示正确');
            }
            /* 当任务为进行中、已暂停状态 */
            if(!is_object($form->dom->modalText)) return $this->failed('任务本次消耗为0时没有提示');
            if($form->dom->modalText->getText() != $this->lang->task->error->consumedEmptyAB) return $this->failed('任务本次消耗为0时提示错误');
        }

        if(is_object($form->dom->confirmBtn)) $form->dom->confirmBtn->click();
        $form->wait(3);
        if($form->dom->taskStatus->getText() != $this->lang->task->statusList->done) return $this->failed('完成任务后状态错误');
        if(floatval($form->dom->taskConsumed->getText()) != floatval($consumed) + floatval($taskConsumed)) return $this->failed('完成任务后总计消耗错误');
        /* 当完成任务弹窗指派给为空，完成任务后，任务指派给为完成者admin */
        if(empty($assignedTo)) $assignedTo = 'admin';
        if($form->dom->taskAssignedTo->getText() != $assignedTo) return $this->failed('完成任务后指派给错误');
        return $this->success('完成任务成功');
    }
}
