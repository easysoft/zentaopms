<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
        $form = $this->initForm('execution', 'view', array('execution' => $executionId ), 'appIframe-execution');
        $status = $form->dom->status->getText();
        $form->dom->btn($this->lang->execution->putoff)->click();
        if(isset($execution['begin'])) $form->dom->begin->datePicker($execution['begin']);
        if(isset($execution['end'])) $form->dom->end->datePicker($execution['end']);
        if(isset($execution['days'])) $form->dom->days->setValue($execution['days']);
        $form->dom->putoffSubmit->click();
        $form->wait(1);
        return $status;
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
        $status = $this->inputFields($execution, $executionId);
        $form = $this->loadPage();

        if($form->dom->putoffSubmit === true) return $this->failed('延期失败');
        if(strtotime($form->dom->plannedEnd->getText()) >= strtotime(date("Y-m-d")))
        {
            if($form->dom->status->getText() != $status) return $this->failed('执行状态错误');
        }
        else
        {
            if($form->dom->status->getText() != $this->lang->execution->statusSelects->delayed) return $this->failed('执行状态错误');
        }
        if(isset($execution['begin']) && $form->dom->plannedBegin->getText() != $execution['begin']) return $this->failed('计划开始时间错误');
        if(isset($execution['end']) && $execution['end'] != '' && $form->dom->plannedEnd->getText() != $execution['end']) return $this->failed('计划完成时间错误');
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

        if($form->dom->putoffSubmit === false) return $this->failed('错误的延期成功');
        if($dateType == 'end')
        {
            $info = sprintf($this->lang->execution->errorCommonEnd, '');
            $text = $form->dom->endTip->getText();
        }
        else
        {
            $info = sprintf($this->lang->execution->errorCommonBegin, '');
            $text = $form->dom->beginTip->getText();
        }
        /* 获取页面返回信息中除日期外的内容 */
        preg_match_all('/(\d{4}-\d{2}-\d{2})/', $text, $matches);                                                                                                                                 ~
        $date   = $matches[0][0];                                                                                                                                                                    ~
        $params = str_replace($date, '', $text);                                                                                                                                                  ~
        $params = trim($params);
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
        if($form->dom->putoffSubmit === false) return $this->failed('错误的延期成功');
        if($form->dom->daysTip->getText() == $this->lang->project->copyProject->daysTips) return $this->success('延期执行表单页提示信息正确');
        return $this->failed('延期执行表单页提示信息不正确');
    }
}
