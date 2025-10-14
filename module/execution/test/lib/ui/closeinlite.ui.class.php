<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class closeExecutionTester extends tester
{
    /**
     * 关闭执行弹窗中输入实际完成日期。
     * Input fields.
     *
     * @param  string $realEnd
     * @param  string $executionId
     * @access public
     */
    public function inputFields($realEnd, $executionId)
    {
        $form = $this->initForm('execution', 'kanban', array('execution' => $executionId), 'appIframe-project');
        $form->wait(1);
        $form->dom->kanbanSettingInLite->click();
        $form->wait(1);
        $form = $this->loadPage();
        $form->wait(1);
        $form->dom->btn($this->lang->execution->close)->click();
        $form->wait(1);
        $form = $this->loadPage();
        $form->wait(1);
        if(isset($realEnd)) $form->dom->realEnd->datePicker($realEnd);
        $form->wait(1);
        $form->dom->closeSubmitInLite->click();
        $form->wait(1);
    }

    /**
     * 正常关闭执行。
     * Close execution.
     *
     * @param  string $realEnd
     * @param  string $executionId
     * @access public
     * @return bool
     */
    public function close($realEnd, $executionId)
    {
        $this->inputFields($realEnd, $executionId);
        $form = $this->initForm('execution', 'view', array('execution' => $executionId), 'appIframe-project');
        $form->wait(1);
        if($form->dom->status->getText() != $this->lang->execution->statusList->closed) return $this->failed('执行状态错误');
        if($form->dom->realEndView->getText() != $realEnd) return $this->failed('执行实际完成日期错误');
        return $this->success('关闭执行成功');
    }

    /**
     * 实际完成日期大于当前日期。
     * The real end date is greater than the current date.
     *
     * @param  string $realEnd
     * @param  string $executionId
     * @access public
     * @return bool
     */
    public function closeWithGreaterDate($realEnd, $executionId)
    {
        $currentVision = $this->page->getCookie('vision');
        if(!isset($currentVision) || $currentVision != 'lite') $this->switchVision('lite');
        $this->inputFields($realEnd, $executionId);
        $form  = $this->loadPage();
        $field = $form->dom->realEndField->getText();
        $text  = $form->dom->realEndTip->getText();
        $info  = sprintf($this->lang->error->le, $field, date('Y-m-d'));
        if($text == $info) return $this->success('关闭执行表单页提示信息正确');
        return $this->failed('关闭执行表单页提示信息不正确');
    }

    /**
     * 实际完成日期小于实际开始日期。
     * The real end date is less than the real start date.
     *
     * @param  string $realEnd
     * @param  string $executionId
     * @access public
     * @return bool
     */
    public function closeWithLessDate($realEnd, $executionId)
    {
        $this->inputFields($realEnd, $executionId);
        $form = $this->loadPage();
        $form->wait(1);
        $field = $form->dom->realEndField->getText();
        $text  = $form->dom->realEndTip->getText();
        $viewForm = $this->initForm('execution', 'view', array('execution' => $executionId), 'appIframe-project');
        $viewForm->wait(1);
        $realBegan = $viewForm->dom->realBeganView->getText();
        $info  = sprintf($this->lang->execution->ge, $field, $realBegan);
        if($text == $info) return $this->success('关闭执行表单页提示信息正确');
        return $this->failed('关闭执行表单页提示信息不正确');
    }
}
