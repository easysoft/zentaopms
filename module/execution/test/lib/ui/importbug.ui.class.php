<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class importBugTester extends tester
{
    /**
     * 导入Bug。
     * Import bug.
     *
     * @param  string $executionId
     * @param  string $expectNum
     * @access public
     * @return void
     */
    public function importBug($executionId, $expectNum)
    {
        $form = $this->initForm('execution', 'task', array('execution' => $executionId), 'appIframe-execution');
        $form->wait(3);
        $form->dom->btn($this->lang->import)->click();
        $form->wait(1);
        $form->dom->btn($this->lang->execution->importBug)->click();
        $form->wait(1);

        $importForm = $this->loadPage('execution', 'importBug');
        $importForm->wait(3);
        if($importForm->dom->num === false)
        {
            if ($expectNum == '0') return $this->success('可导入的Bug数目正确');
            return $this->failed('可导入的Bug数目不正确');
        }
        if($importForm->dom->num->getText() != $expectNum) return $this->failed('可导入的Bug数目不正确');
        $id = $importForm->dom->firstId->getText();
        $importForm->dom->firstCheckbox->click();
        $importForm->dom->saveBtn->click();
        $importForm->wait(3);
        $importForm->dom->btn($this->lang->goback)->click();
        $importForm->wait(1);

        $form->dom->search(array("{$this->lang->task->fromBugID},=,{$id}"));
        if($form->dom->firstName === false) return $this->failed('导入Bug失败');
        return $this->success('导入Bug成功');
    }
}
