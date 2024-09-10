<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class taskExecutionTester extends tester
{
    /**
     * 检查Tab标签下的数据。
     * Check the data of the Tab tag.
     *
     * @param  string $tab
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('execution', 'task', array('execution' => '2'), 'appIframe-execution');
        $params = array('allTab', 'unclosedTab', 'myTab', 'involvedTab','assignedByMeTab', 'changedByMeTab');
        if(!in_array($tab, $params)) $form->dom->MoreTab->click();
        $form->wait(1);
        $form->dom->$tab->click();
        $form->wait(1);
        if($form->dom->num->getText() == $expectNum) return $this->success($tab . '下显示条数正确');
        return $this->failed($tab . '下显示条数不正确');
    }

    /**
     * 批量编辑状态
     * Batch edit status
     *
     * @param  string $status closed|cancel
     * @access public
     * @return object
     */
    public function batchEditStatus($status)
    {
        $form = $this->initForm('execution', 'task', array('execution' => '2'), 'appIframe-execution');
        $name = $form->dom->firstName->getText();
        $btn  = $status . 'Btn';
        $form->dom->firstCheckbox->click();
        $form->dom->statusBtn->click();
        $form->wait(1);
        $form->dom->$btn->click();
        $form->wait(1);
        try
        {
            $form->dom->alertModal();
        }
        catch(Exception $e)
        {
        }
        $form->wait(1);
        $form->dom->search(array("{$this->lang->task->name},=,{$name}"));
        $form->wait(1);
        $statusAfter = $form->dom->firstStatus->getText();
        if($this->lang->task->statusList->$status == $statusAfter) return $this->success("批量修改状态为{$status}成功");
        return $this->failed("批量修改状态为{$status}失败");
    }
}
