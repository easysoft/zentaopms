<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class restartTaskTester extends tester
{
    /**
     * 继续任务。
     * Restart task.
     *
     * @param  string $id
     * @param  string $consumed
     * @param  string $left
     * @param  string $status
     * @access public
     * @return object
     */
    public function restart($id, $consumed, $left, $status)
    {
        $form = $this->initForm('task', 'view', array('taskID' => $id), 'appIframe-execution');

        $form->dom->xpath['taskConsumed']   = "//*[@title='{$this->lang->task->consumed}']/../div[2]";
        $form->dom->xpath['taskLeft']       = "//*[@title='{$this->lang->task->left}']/../div[2]";
        $form->dom->xpath['restartBtn']     = "//a[@title='{$this->lang->task->restart}']";
        $taskConsumed = $form->dom->taskConsumed->getText();

        /* 只有已暂停的任务才显示继续任务按钮 */
        if($status != 'pause')
        {
            if(is_object($form->dom->pauseBtn)) return $this->failed('错误的显示了继续按钮');
            return $this->success('没有显示继续按钮');
        }
        $form->dom->restartBtn->click();
        $form->wait(1);
        /* 输入继续任务表单内容 */
        if(!empty($assignedTo)) $form->dom->assignedTo->picker($assignedTo);
        $form->wait(1);
        $form->dom->consumed->setValue($consumed);
        $form->dom->left->setValue($left);
        $form->dom->submitBtn->click();
        $form->wait(1);

        if((empty($consumed) || $consumed == '0') && (empty($left) || $left == '0'))
        {
            if(!is_object($form->dom->modalText)) return $this->failed('总计消耗和预计剩余都为空或0时没有提示');
            if($form->dom->modalText->getText() != $this->lang->task->noticeTaskStart) return $this->failed('总计消耗和预计剩余都为空或0时提示错误');
            return $this->success('总计消耗和预计剩余都为空或0时提示正确');
        }
        elseif($consumed != '0' && $taskConsumed <= $consumed && (empty($left) || $left == '0'))
        {
            if(!is_object($form->dom->modalText)) return $this->failed('预计剩余都为空或0时没有提示');
            if($form->dom->modalText->getText() != $this->lang->task->confirmFinish) return $this->failed('预计剩余为空或0时提示错误');
            $form->dom->confirmBtn->click();
            $form->wait(3);
            if($form->dom->taskStatus->getText() != $this->lang->task->statusList->done) return $this->failed('预计剩余为空或0时任务没有完成');
        }
        elseif(floatval($taskConsumed) > floatval($consumed))
        {
            if(!is_object($form->dom->consumedTip)) return $this->failed('总计消耗小于之前消耗时没有提示');
            if($form->dom->consumedTip->getText() != $this->lang->task->error->consumedSmall) return $this->failed('总计消耗小于之前消耗时提示错误');
            return $this->success('总计消耗小于之前消耗时提示正确');
        }
        else
        {
            $form->wait(3);
            if($form->dom->taskStatus->getText() != $this->lang->task->statusList->doing) return $this->failed('继续任务后状态错误');
        }
        if(intval($form->dom->taskConsumed->getText()) != $consumed) return $this->failed('继续任务后总计消耗错误');
        if(intval($form->dom->taskLeft->getText()) != $left)         return $this->failed('继续任务后预计剩余错误');
        return $this->success('继续任务成功');
    }
}
