<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class startTaskTester extends tester
{
    /**
     * 检查父任务的开始按钮。
     * Check start button.
     *
     * @access public
     * @return object
     */
    public function checkStartBtn()
    {
        /* 父任务的开始按钮置灰,创建的yaml数据中id为1的任务是父任务 */
        $form = $this->initForm('execution', 'task', array('executionID' => '2'), 'appIframe-execution');
        $form->wait(1);
        $form->dom->xpath['startBtn'] = "//*[@data-col='actions' and @data-row='1']//button[@title='{$this->lang->task->start}']";
        if($form->dom->startBtn->attr('href')) return $this->failed('父任务开始按钮没有置灰');
        return $this->success('父任务开始按钮置灰');
    }

    /**
     * 开始任务。
     * Start task.
     *
     * @param  string $id
     * @param  string $assignedTo
     * @param  string $consumed
     * @param  string $left
     * @access public
     * @return object
     */
    public function start($id, $assignedTo, $consumed, $left)
    {
        $form = $this->initForm('task', 'view', array('taskID' => $id), 'appIframe-execution');

        $form->dom->xpath['taskAssignedTo'] = "//*[@title='{$this->lang->task->assignedTo}']/../div[2]";
        $form->dom->xpath['taskConsumed']   = "//*[@title='{$this->lang->task->consumed}']/../div[2]";
        $form->dom->xpath['taskLeft']       = "//*[@title='{$this->lang->task->left}']/../div[2]";
        $form->dom->xpath['startBtn']       = "//a[@title='{$this->lang->task->start}']";

        $form->dom->startBtn->click();
        $form->wait(1);
        /* 输入开始任务表单内容 */
        if(empty($assignedTo))  $form->dom->assignedToDelBtn->click();
        if(!empty($assignedTo)) $form->dom->assignedTo->picker($assignedTo);
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
        elseif($consumed != '0' && (empty($left) || $left == '0'))
        {
            if(!is_object($form->dom->modalText)) return $this->failed('预计剩余都为空或0时没有提示');
            if($form->dom->modalText->getText() != $this->lang->task->confirmFinish) return $this->failed('预计剩余为空或0时提示错误');
            $form->dom->confirmBtn->click();
            $form->wait(3);
            if($form->dom->taskStatus->getText() != $this->lang->task->statusList->done) return $this->failed('预计剩余为空或0时任务没有完成');
        }
        else
        {
            $form->wait(3);
            if($form->dom->taskStatus->getText() != $this->lang->task->statusList->doing) return $this->failed('开始任务后状态错误');
        }
        if($form->dom->taskAssignedTo->getText() != $assignedTo)     return $this->failed('开始任务后指派给错误');
        if(intval($form->dom->taskConsumed->getText()) != $consumed) return $this->failed('开始任务后总计消耗错误');
        if(intval($form->dom->taskLeft->getText()) != $left)         return $this->failed('开始任务后预计剩余错误');
        return $this->success('开始任务成功');
    }
}
