<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
Class activateExecutionTester extends tester
{
    /**
     * 输入激活执行表单字段。
     * Input fields.
     *
     * @param  string $end
     * @param  string $executionId
     * @access public
     */
    public function inputFields($end, $executionId)
    {
        $form = $this->initForm('execution', 'view', array('execution' => $executionId ), 'appIframe-execution');
        $form->dom->btn($this->lang->execution->activate)->click();
        if(isset($end)) $form->dom->end->datePicker($end);
        $form->dom->activateSubmit->click();
        $form->wait(1);
    }

    /**
     * 激活执行。
     * Activate execution.
     *
     * @param  string $end
     * @param  string $executionId
     * @access public
     * @return bool
     */
    public function activate($end, $executionId)
    {
        $this->inputFields($end, $executionId);
        $form = $this->loadPage();

        if($form->dom->activateSubmit === true) return $this->failed('激活执行失败');
        if($form->dom->status->getText() != $this->lang->execution->statusList->doing) return $this->failed('执行状态错误');
        if($form->dom->plannedEnd->getText() != $end) return $this->failed('计划完成时间错误');
        return $this->success('激活执行成功');
    }

    /**
     * 激活执行时计划完成日期错误。
     * Activate execution with wrong planned end.
     *
     * @param  string $end
     * @param  string $executionId
     * @access public
     * @return bool
     */
    public function activateWithWrongEnd($end, $executionId)
    {
        $this->inputFields($end, $executionId);
        $form  = $this->loadPage();
        $begin = $form->dom->begin->getText();
        $info  = sprint($this->lang->execution->errorLesserPlan, $end, $begin )
        if($form->dom->endTip->getText() == $info) return $this->success('激活执行表单页提示信息正确');
        return $this->failed('激活执行表单页提示信息不正确');
    }
}
