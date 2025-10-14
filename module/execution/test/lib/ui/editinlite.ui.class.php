<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class editExecutionTester extends tester
{
    /**
     * 输入表单字段内容。
     * Input fields
     *
     * @param  array $execution
     * @access public
     */
    public function inputFields($execution)
    {
        $form = $this->initForm('execution', 'kanban', array('kanbanID' => '2'), 'appIframe-project');
        $form->wait(1);
        $form->dom->kanbanSettingInLite->click();
        $form->wait();
        $form = $this->loadPage();
        $form->wait(1);
        $form->dom->btn($this->lang->execution->setKanban)->click();
        $form->wait(1);
        if(isset($execution['name']))  $form->dom->name->setValue($execution['name']);
        if(isset($execution['begin'])) $form->dom->begin->datePicker($execution['begin']);
        if(isset($execution['end']))   $form->dom->end->datePicker($execution['end']);
        $form->dom->editSubmitInLite->click();
        $form->wait(1);
    }

    /**
     * 看板名称已存在时获取提示信息。
     * Get error info of repeat name.
     *
     * @access public
     * @return bool
     */
    public function checkRepeatInfo()
    {
        $form = $this->loadPage();
        $form->wait(1);
        if(!is_object($form->dom->nameTip)) return $this->failed('看板名称重复没有提示信息');
        $text = $form->dom->nameTip->getText();
        $info = sprintf($this->lang->error->repeat, $this->lang->kanban->name, $form->dom->name->getValue());
        if($text == $info) return $this->success('编辑看板表单页提示信息正确');
        return $this->failed('编辑看板表单页提示信息不正确');
    }

    /**
     * 计划起止日期错误时获取提示信息。
     * Get error info of begin and end date.
     *
     * @param  string $dateType begin|end
     * @access public
     * @return bool
     */
    public function checkDateInfo($dateType = 'end')
    {
        $form = $this->loadPage();
        $form->wait(1);
        if($dateType == 'begin')
        {
            $text = $form->dom->beginTip->getText();
            if($this->config->default->lang == 'zh-cn') $info = '看板开始日期应大于等于项目的开始日期：。';
            if($this->config->default->lang == 'en')    $info = 'The start date of Kanban should be ≥ the start date of Project : .';
        }
        else
        {
            $text = $form->dom->endTip->getText();
            if($this->config->default->lang == 'zh-cn') $info = '看板截止日期应小于等于项目的截止日期：。';
            if($this->config->default->lang == 'en')    $info = 'The deadline of Kanban should be ≤ the deadline of Project : .';
        }

        /* 获取页面返回信息中除日期外的内容 */
        preg_match_all('/(\d{4}-\d{2}-\d{2})/', $text, $matches);
        $date   = $matches[0];
        $params = str_replace($date, '', $text);
        $params = trim($params);

        return $params == $info;
    }

    /**
     * 编辑看板。
     * Edit kanban.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function edit($execution)
    {
        $currentVision = $this->page->getCookie('vision');
        if(!isset($currentVision) || $currentVision != 'lite') $this->switchVision('lite');
        $this->inputFields($execution);
        if($this->checkFormTips('execution')) return $this->success('编辑看板表单页提示信息正确');

        /* 访问view方法，方便获取信息 */
        $form = $this->initForm('execution', 'view', array('kanbanID' => '2' ), 'appIframe-project');
        if(isset($execution['name']) && $form->dom->executionName->getText() != $execution['name'])        return $this->failed('编辑看板名称失败');
        if(isset($execution['begin']) && $form->dom->plannedBeginInLite->getText() != $execution['begin']) return $this->failed('编辑预计开始日期失败');
        if(isset($execution['end']) && $form->dom->plannedEndInLite->getText() != $execution['end'])       return $this->failed('编辑预计结束日期失败');
        return $this->success('编辑看板成功');
    }

    /**
     * 看板名称为空编辑执行。
     * Edit kanban with no name.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function editWithEmptyName($execution)
    {
        $this->inputFields($execution);
        $form = $this->loadPage();
        $form->wait(1);
        $text = $form->dom->nameTip->getText();
        $info = sprintf($this->lang->error->notempty, $this->lang->kanban->name);
        if($text == $info) return $this->success('编辑看板表单页提示信息正确');
        return $this->failed('编辑看板表单页提示信息不正确');
    }

    /**
     * 看板名称已存在编辑执行。
     * Edit kanban with repeat name.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function editWithRepeatName($execution)
    {
        $this->inputFields($execution);
        return $this->checkRepeatInfo();
    }

    /**
     * 看板起止日期错误编辑看板。
     * Edit kanban with date error.
     *
     * @param  array  $execution
     * @param  string $dateType   begin|end
     * @access public
     * @return object
     */
    public function editWithDateError($execution, $dateType = 'end')
    {
        $this->inputFields($execution);
        if($this->checkDateInfo($dateType)) return $this->success('编辑看板表单页提示信息正确');
        return $this->failed('编辑看板表单页提示信息不正确');
    }
}
