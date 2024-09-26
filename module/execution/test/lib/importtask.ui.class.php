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
     * @access public
     * @return void
     */
    public function importTask($execution, $expectNum)
    {
        $form = $this->initForm('execution', 'task', array('execution' => '3'), 'appIframe-execution');
        $form->dom->btn($this->lang->import)->click();
        $form->dom->btn($this->lang->execution->importTask)->click();

        $importForm = $this->loadPage('execution', 'importTask');
        $importForm->dom->pickInput->click();
        $form->wait(1);
        var_dump($form->dom->getItems('execution'));die;
        $executionList = $form->dom->getElementList($form->dom->executionList);
        var_dump($executionList);die;

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
