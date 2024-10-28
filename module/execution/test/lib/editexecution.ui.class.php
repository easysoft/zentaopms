<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editExecutionTester extends tester
{
    /**
     * 输入表单字段内容。
     * Edit fields
     *
     * @param  array $execution
     * @access public
     */
    public function editFields($execution)
    {
        $form = $this->initForm('execution', 'view', array('execution' => '101'), 'appIframe-execution');
        $form->wait(1);
        $form->dom->edit->click();
        if(isset($execution['project'])) $form->dom->project->picker($execution['project']);
        $form = $this->loadPage();
        $form->wait(1);
        if(isset($execution['name'])) $form->dom->name->setValue($execution['name']);
        if(isset($execution['begin'])) $form->dom->begin->datePicker($execution['begin']);
        if(isset($execution['end'])) $form->dom->end->datePicker($execution['end']);
        if(isset($execution['products'])) $form->dom->products->picker($execution['products']);
        $form->dom->submit->click();
        $form->wait(1);
        return $form;
    }

    /**
     * 编辑执行。
     * Edit the execution .
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function edit($execution)
    {
        $form = $this->editFields($execution);
        /* 根据编辑弹窗中的保存按钮是否存在，判断是否编辑成功 */
        if($form->dom->submit === true) return $this->failed("编辑执行失败");
        /* 查看相关内容是否正确 */
        $viewPage = $this->loadPage('execution', 'view');
        if(isset($execution['name']) && ($viewPage->dom->executionName->getText() != $execution['name']))  return $this->failed('编辑后执行名称错误');
        if(isset($execution['begin']) && ($viewPage->dom->plannedBegin->getText() != $execution['begin'])) return $this->failed('编辑后计划开始时间错误');
        if(isset($execution['end']) && ($viewPage->dom->plannedEnd->getText() != $execution['end']))       return $this->failed('编辑后计划完成时间错误');
        return $this->success('编辑执行成功');
    }
}
