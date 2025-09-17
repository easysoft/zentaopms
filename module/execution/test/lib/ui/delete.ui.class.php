<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class deleteExecutionTester extends tester
{
    /**
     * 删除执行。
     * Delete execution.
     *
     * @access public
     * @return object
     */
    public function delete()
    {
        $viewForm = $this->initForm('execution', 'view', array('execution' => '2'), 'appIframe-execution');
        $viewForm->wait(5);
        $executionName = $viewForm->dom->executionName->getText();
        $viewForm->dom->deleteBtn->click();
        $viewForm->wait(1);
        $viewForm->dom->alertModal();
        $viewForm->wait(1);

        $form = $this->initForm('execution', 'all', '', 'appIframe-execution');
        $form->wait(1);
        $form->dom->search(array(",=,{$executionName}"));
        $form->wait(1);
        if(is_object($form->dom->tips) && $form->dom->tips->getText() == $this->lang->execution->noExecutions) return $this->success('删除执行成功');
        return $this->failed('删除执行失败');
    }
}
