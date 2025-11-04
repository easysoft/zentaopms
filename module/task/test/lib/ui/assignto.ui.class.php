<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class assignToTester extends tester
{
    /**
     * 指派任务
     * Assign task.
     *
     * @param  string $taskID
     * @param  string $account
     * @param  string $left
     * @access public
     * @return object
     */
    public function assignTo($taskID, $account, $left)
    {
        /* 任务详情页指派 */
        $form = $this->initForm('task', 'view', array('taskID' => $taskID), 'appIframe-execution');

        $form->dom->xpath['assignTo']  = "//*[@title='{$this->lang->task->assignedTo}']/../div[2]";
        $form->dom->xpath['taskLeft']  = "//*[@title='{$this->lang->task->left}']/../div[2]";
        $form->dom->xpath['assignBtn'] = "//a[@title='{$this->lang->task->assign}']";

        if($form->dom->taskStatus->getText() == $this->lang->task->statusList->closed)
        {
            if(is_object($form->dom->assignBtn)) return $this->failed('已关闭的任务详情页错误的显示了指派按钮');

            /* 查看任务列表中已关闭任务的指派 */
            $taskForm = $this->initForm('execution', 'task', array('executionID' => '2', 'status' => 'all'), 'appIframe-execution');
            $taskForm->dom->xpath['status']     = "//div[@id='tasks']//*[@data-row='{$taskID}' and @data-type='status']//span";
            $taskForm->dom->xpath['assign']     = "//div[@id='tasks']//*[@data-row='{$taskID}' and @data-type='assign']//span";
            $taskForm->dom->xpath['assignAttr'] = "//div[@id='tasks']//*[@data-row='{$taskID}' and @data-type='assign']//a";

            if($taskForm->dom->assign->getText()!== 'Closed')                             return $this->failed('任务列表已关闭的任务指派人不是Closed');
            if($taskForm->dom->assignAttr->attr('class') != 'dtable-assign-btn disabled') return $this->failed('任务列表已关闭的任务指派按钮可点击');
            return $this->success('已关闭的任务没有显示指派按钮');
        }
        /* 已取消的任务目前列表可以指派，详情页未显示指派按钮 */
        if($form->dom->taskStatus->getText() == $this->lang->task->statusList->cancel)
        {
            if(!is_object($form->dom->assignBtn)) return $this->success('已取消的任务详情页没有显示指派按钮');
        }
        /* 指派弹窗 */
        $form->dom->assignBtn->click();
        $form->wait(1);
        if(empty($account)) $form->dom->assignedToDelBtn->click();
        if(!empty($account)) $form->dom->assignedTo->picker($account);
        if($form->dom->taskStatus->getText() != $this->lang->task->statusList->done) $form->dom->left->setValue($left);
        $form->dom->submitBtn->click();
        $form->wait(1);
        /* 校验预计剩余错误校验 */
        if(floatval($left) <= 0)
        {
            if(!is_object($form->dom->leftTip)) return $this->failed('预计剩余内容错误没有提示');
            if(!is_object($form->dom->leftTip->getText()) != sprintf($this->lang->error->notempty, $this->lang->task->left)) return $this->failed('预计剩余内容错误提示错误');
            return $this->success('预计剩余内容错误提示正确');
        }
        $form->wait(1);
        if($form->dom->assignTo->getText() != $account) return $this->failed('指派人错误');
        if($form->dom->taskStatus->getText() != $this->lang->task->statusList->done && floatval($form->dom->taskLeft->getText()) != floatval($left)) return $this->failed('预计剩余错误');
        return $this->success('指派任务成功');
    }
}
