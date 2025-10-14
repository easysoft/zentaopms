<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class activateTaskTester extends tester
{
    /**
     * 激活任务。
     * Activate task.
     *
     * @param  string $id
     * @param  string $assignedTo
     * @param  string $left
     * @param  string $status
     * @access public
     * @return object
     */
    public function activate($id, $assignedTo, $left, $status)
    {
        $form = $this->initForm('task', 'view', array('taskID' => $id), 'appIframe-execution');

        $form->dom->xpath['taskAssignedTo'] = "//*[@title='{$this->lang->task->assignedTo}']/../div[2]";
        $form->dom->xpath['taskStatus']     = "//*[@title='{$this->lang->task->status}']/..//span";
        $form->dom->xpath['taskLeft']       = "//*[@title='{$this->lang->task->left}']/../div[2]";
        $form->dom->xpath['activateBtn']    = "//a[@title='{$this->lang->task->activate}']";

        if(!in_array($status, array('done', 'cancel', 'closed')))
        {
            if(is_object($form->dom->activateBtn)) return $this->failed('错误的显示了激活按钮');
            return $this->success('没有显示激活按钮');
        }

        $form->dom->activateBtn->click();
        $form->wait(1);
        /* 输入激活任务表单内容 */
        if(!empty($assignedTo)) $form->dom->assignedTo->picker($assignedTo);
        $form->dom->left->setValue($left);
        $form->dom->submitBtn->click();
        $form->wait(1);

        if(empty($left) || $left == '0')
        {
            if(!is_object($form->dom->leftTip)) return $this->failed('预计剩余为空或0时没有提示');
            if($form->dom->leftTip->getText() != sprintf($this->lang->error->notempty, $this->lang->task->left)) return $this->failed('预计剩余为空或0时提示错误');
            return $this->success('预计剩余为空或0时提示正确');
        }
        $form->wait(1);
        if($form->dom->taskAssignedTo->getText() != $assignedTo)                      return $this->failed('激活任务后指派给错误');
        if(floatval($form->dom->taskLeft->getText()) != $left)                        return $this->failed('激活任务后预计剩余错误');
        if($form->dom->taskStatus->getText() != $this->lang->task->statusList->doing) return $this->failed('激活任务后任务状态错误');
        return $this->success('激活任务成功');
    }
}
