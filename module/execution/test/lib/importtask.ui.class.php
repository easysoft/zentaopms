<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class importTaskTester extends tester
{
    /**
     * 执行下导入任务。
     * Import task.
     *
     * @param  string $execution
     * @param  string $expectNum
     * @param  string $exist
     * @access public
     * @return void
     */
    public function importTask($execution, $expectNum, $exist = '1')
    {
        $form = $this->initForm('execution', 'task', array('execution' => '3'), 'appIframe-execution');
        $form->dom->btn($this->lang->import)->click();
        $form->dom->btn($this->lang->execution->importTask)->click();

        $importForm = $this->loadPage('execution', 'importTask');
        $importForm->wait(1);

        $executionList = $importForm->dom->getPickerItems('execution');
        $executions    = array_column($executionList, 'text');
        if(!in_array($execution, $executions))
        {
            if($exist == '0') return $this->success('执行下拉列表执行显示正确');
            return $this->falled('执行下拉列表执行显示错误');
        }
        if($exist == '0') return $this->failed('执行下拉列表执行显示错误');

        $importForm->dom->execution->picker($execution);
        $importForm->wait(1);
        if($importForm->dom->num->getText() != $expectNum) return $this->failed('可导入需求不正确');
        $name = $importForm->dom->firstName->getText();
        $importForm->dom->firstCheckbox->click();
        $importForm->dom->saveBtn->click();
        $form->wait(1);
        $importForm->dom->btn($this->lang->goback)->click();

        $form->dom->search(array("{$this->lang->task->name},=,{$name}"));
        if($form->dom->firstName === false) return $this->failed('导入任务失败');
        return $this->success('导入任务成功');
    }
}
