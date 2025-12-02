<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class startExecutionTester extends tester
{
    /**
     * 开始执行弹窗中输入实际开始日期。
     * Input fields.
     *
     * @param  string $realBegan
     * @param  string $executionId
     * @access public
     */
    public function inputFields($realBegan, $executionId = '2')
    {
        $form = $this->initForm('execution', 'view', array('execution' => $executionId ), 'appIframe-execution');
        $form->wait(1);
        $form->dom->btn($this->lang->execution->start)->click();
        $form->wait(1);
        if(isset($realBegan)) $form->dom->realBegan->datePicker($realBegan);
        $form->wait(1);
        $form->dom->startSubmit->click();
        $form->wait(1);
    }

    /**
     * 正常开始执行。
     * Start execution.
     *
     * @param  string $realBegan
     * @access public
     * @return bool
     */
    public function start($realBegan)
    {
        $this->inputFields($realBegan);
        $form = $this->loadPage();
        $form->wait(3);
        if($form->dom->status->getText() != $this->lang->execution->statusList->doing) return $this->failed('执行状态错误');
        if($form->dom->realBeganView->getText() != $realBegan) return $this->failed('执行实际开始日期错误');
        return $this->success('开始执行成功');
    }

    /**
     * 实际开始日期大于当前日期。
     * The real start date is greater than the current date.
     *
     * @param  string $realBegan
     * @access public
     * @return bool
     */
    public function startWithGreaterDate($realBegan)
    {
        $this->inputFields($realBegan);
        $form  = $this->loadPage();
        $field = $form->dom->realBeganField->getText();
        $text  = $form->dom->realBeganTip->getText();
        $info  = sprintf($this->lang->error->le, $field, $realBegan);
        if($text != $info) return $this->success('开始执行表单页提示信息正确');
        return $this->failed('开始执行表单页提示信息不正确');
    }
}
