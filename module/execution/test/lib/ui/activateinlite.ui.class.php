<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class activateExecutionTester extends tester
{
    /**
     * 输入激活看板表单字段。
     * Input fields.
     *
     * @param  string $end
     * @param  string $executionId
     * @access public
     */
    public function inputFields($end, $executionId)
    {
        $form = $this->initForm('execution', 'kanban', array('execution' => $executionId), 'appIframe-project');
        $form->wait(1);
        $form->dom->kanbanSettingInLite->click();
        $form->wait(1);
        $this->loadPage();
        $form->wait(1);
        $form->dom->btn($this->lang->execution->activate)->click();
        $form->wait(1);
        $this->loadPage();
        $form->wait(1);
        if(isset($end)) $form->dom->end->datePicker($end);
        $form->dom->activateSubmitInLite->click();
        $form->wait(1);
    }

    /**
     * 激活看板。
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
        $form->wait(1);

        if(is_object($form->dom->activateSubmitInLite)) return $this->failed('激活看板失败');
        $viewForm = $this->initForm('execution', 'view', array('execution' => $executionId), 'appIframe-project');
        $viewForm->wait(1);
        if($viewForm->dom->plannedEnd->getText() != $end) return $this->failed('计划完成时间错误');
        return $this->success('激活看板成功');
    }

    /**
     * 激活看板时计划完成日期小于计划开始日期。
     * Activate execution with end less than begin.
     *
     * @param  string $end
     * @param  string $executionId
     * @access public
     * @return bool
     */
    public function activateWithLessEnd($end, $executionId)
    {
        $currentVision = $this->page->getCookie('vision');
        if(!isset($currentVision) || $currentVision != 'lite') $this->switchVision('lite');
        $this->inputFields($end, $executionId);
        $form = $this->loadPage();
        $form->wait(1);
        $info = sprintf($this->lang->execution->errorLesserPlan, $end, date('Y-m-d'));
        if($form->dom->endTip->getText() == $info) return $this->success('激活看板表单页提示信息正确');
        return $this->failed('激活看板表单页提示信息不正确');
    }

    /**
     * 激活看板时计划完成日期大于项目计划完成日期。
     * Activate execution with end greater than begin.
     *
     * @param  string $end
     * @param  string $executionId
     * @access public
     * @return bool
     */
    public function activateWithGreaterEnd($end, $executionId)
    {
        $this->inputFields($end, $executionId);
        $form = $this->loadPage();
        $form->wait(1);
        $info = sprintf($this->lang->execution->errorGreaterParent, '');
        $text = $form->dom->endTip->getText();
        /* 获取页面返回信息中除日期外的内容 */
        preg_match_all('/(\d{4}-\d{2}-\d{2})/', $text, $matches);                                                                                                                                 ~
        $date   = $matches[0][0];                                                                                                                                                                    ~
        $params = str_replace($date, '', $text);                                                                                                                                                  ~
        $params = trim($params);
        if($params == $info) return $this->success('激活看板表单页提示信息正确');
        return $this->failed('激活看板表单页提示信息不正确');
    }
}
