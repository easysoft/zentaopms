<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class startExecutionTester extends tester
{
    /**
     * 开始看板弹窗中输入实际开始日期。
     * Input fields.
     *
     * @param  string $realBegan
     * @param  string $kanbanId
     * @access public
     */
    public function inputFields($realBegan, $kanbanId)
    {

        $form = $this->initForm('execution', 'kanban', array('kanbanID' => $kanbanId ), 'appIframe-project');
        $form->wait(1);
        $form->dom->kanbanSettingInLite->click();
        $form->wait(1);
        $form->dom->btn($this->lang->execution->start)->click();
        $form->wait(1);
        if(isset($realBegan)) $form->dom->realBegan->datePicker($realBegan);
        $form->wait(1);
        $form->dom->startSubmitInLite->click();
        $form->wait(1);
    }

    /**
     * 正常开始看板。
     * Start kanban.
     *
     * @param  string $realBegan
     * @param  string $kanbanId
     * @access public
     * @return bool
     */
    public function start($realBegan, $kanbanId)
    {
        $this->inputFields($realBegan, $kanbanId);
        $form = $this->initForm('execution', 'view', array('kanbanID' => $kanbanId ), 'appIframe-project');
        if($form->dom->status->getText() != $this->lang->execution->statusList->doing) return $this->failed('看板状态错误');
        if($form->dom->realBeganView->getText() != $realBegan) return $this->failed('看板实际开始日期错误');
        return $this->success('开始看板成功');
    }

    /**
     * 实际开始日期大于当前日期。
     * The real start date is greater than the current date.
     *
     * @param  string $realBegan
     * @param  string $kanbanId
     * @access public
     * @return bool
     */
    public function startWithGreaterDate($realBegan, $kanbanId)
    {
        $currentVision = $this->page->getCookie('vision');
        if(!isset($currentVision) || $currentVision != 'lite') $this->switchVision('lite');
        $this->inputFields($realBegan, $kanbanId);
        $form  = $this->loadPage();
        $field = $form->dom->realBegan->getValue();
        $text  = $form->dom->realBeganTip->getText();
        $info  = sprintf($this->lang->error->le, $field, $realBegan);
        if($text != $info) return $this->success('开始看板表单页提示信息正确');
        return $this->failed('开始看板表单页提示信息不正确');
    }
}
