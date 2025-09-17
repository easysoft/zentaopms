<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class putoffExecutionTester extends tester
{
    /**
     * 输入延期执行表单字段。
     * Input fields.
     *
     * @param  array  $execution
     * @param  string $executionId
     * @access public
     * @return bool
     */
    public function inputFields($execution, $executionId)
    {
        $form = $this->initForm('execution', 'kanban', array('kanbanID' => $executionId ), 'appIframe-project');
        $form->wait(1);
        $form->dom->kanbanSettingInLite->click();
        $form->wait(1);
        $form->dom->btn($this->lang->execution->putoff)->click();
        if(isset($execution['begin'])) $form->dom->begin->datePicker($execution['begin']);
        if(isset($execution['end']))   $form->dom->end->datePicker($execution['end']);
        if(isset($execution['days']))  $form->dom->days->setValue($execution['days']);
        $form->dom->putoffSubmitInLite->click();
        $form->wait(1);
    }

    /**
     * 延期执行。
     * Putoff execution.
     *
     * @param  array  $execution
     * @param  string $executionId
     * @access public
     * @return bool
     */
    public function putoff($execution, $executionId)
    {
        $currentVision = $this->page->getCookie('vision');
        if(!isset($currentVision) || $currentVision != 'lite') $this->switchVision('lite');
        $this->inputFields($execution, $executionId);
        $form = $this->loadPage();
        $form->wait(1);
        if(is_object($form->dom->putoffSubmitInLite)) return $this->failed('延期失败');

        $form = $this->initForm('execution', 'view', array('kanbanID' => $executionId ), 'appIframe-project');
        if(isset($execution['begin']) && $form->dom->plannedBeginInLite->getText() != $execution['begin']) return $this->failed('计划开始时间错误');
        if(isset($execution['end']) && $form->dom->plannedEndInLite->getText() != $execution['end'])       return $this->failed('计划完成时间错误');
        return $this->success('延期执行成功');
    }

    /**
     * 延期执行的计划起止日期不正确。
     * Putoff execution with wrong date.
     *
     * @param  array  $execution
     * @param  string $executionId
     * @param  string $dateType    begin|end
     * @access public
     * @return bool
     */
    public function putoffWithWrongDate($execution, $executionId, $dateType = 'end')
    {
        $this->inputFields($execution, $executionId);
        $form = $this->loadPage();
        $form->wait(1);

        if(!is_object($form->dom->putoffSubmitInLite)) return $this->failed('错误的延期成功');

        if($dateType == 'end' && $execution['end'] != '')
        {
            $text = $form->dom->endTip->getText();
            if($this->config->default->lang == 'zh-cn') $info = '看板截止日期应小于等于项目的截止日期：。';
            if($this->config->default->lang == 'en')    $info = 'The deadline of Kanban should be ≤  the deadline of Project : .';
        }
        if($dateType == 'begin')
        {
            $text = $form->dom->beginTip->getText();
            if($this->config->default->lang == 'zh-cn') $info = '看板开始日期应大于等于项目的开始日期：。';
            if($this->config->default->lang == 'en')    $info = 'The start date of Kanban should be ≥  the start date of Project : .';
        }
        /* 计划结束日期为空时，错误提示中没有日期 */
        if($dateType == 'end' && $execution['end'] == '')
        {
            $info   = sprintf($this->lang->error->notempty, $this->lang->execution->end);
            $params = $form->dom->endTip->getText();
        }
        else
        {
            /* 获取页面返回信息中除日期外的内容 */
            preg_match_all('/(\d{4}-\d{2}-\d{2})/', $text, $matches);                                                                                                                                 ~
            $date   = $matches[0][0];                                                                                                                                                                    ~
            $params = str_replace($date, '', $text);                                                                                                                                                  ~
            $params = trim($params);
        }

        if($params == $info) return $this->success('延期执行表单页提示信息正确');
        return $this->failed('延期执行表单页提示信息不正确');
    }

    /**
     * 延期执行的可用工日不正确。
     * Putoff execution with wrong days.
     *
     * @param  array  $execution
     * @param  string $executionId
     * @access public
     * @return bool
     */
    public function putoffWithWrongDays($execution, $executionId)
    {
        $this->inputFields($execution, $executionId);
        $form = $this->loadPage();
        $form->wait(1);
        if(!is_object($form->dom->putoffSubmitInLite)) return $this->failed('错误的延期成功');
        if($form->dom->daysTip->getText() == $this->lang->project->copyProject->daysTips) return $this->success('延期执行表单页提示信息正确');
        return $this->failed('延期执行表单页提示信息不正确');
    }
}
