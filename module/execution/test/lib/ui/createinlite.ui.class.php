<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createExecutionTester extends tester
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
        $form = $this->initForm('execution', 'create', '', 'appIframe-project');
        $form->wait(1);
        if(isset($execution['project'])) $form->dom->project->picker($execution['project']);
        $form = $this->loadPage();
        $form->wait(1);
        if(isset($execution['name']))  $form->dom->name->setValue($execution['name']);
        if(isset($execution['begin'])) $form->dom->begin->datePicker($execution['begin']);
        if(isset($execution['end']))   $form->dom->end->datePicker($execution['end']);
        $form->dom->btn($this->lang->save)->click();
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
        $text = $form->dom->nameTip->getText();
        $info = sprintf($this->lang->error->repeat, $this->lang->kanban->name, $form->dom->name->getValue());

        return $text == $info;
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
     * 创建看板。
     * Create a new execution .
     *
     * @param  array  $execution
     * @param  string $module    execution|kanban
     * @access public
     * @return object
     */
    public function create($execution, $module = 'execution')
    {
        $currentVision = $this->page->getCookie('vision');
        if(!isset($currentVision) || $currentVision != 'lite') $this->switchVision('lite');
        $this->inputFields($execution);

        /* 创建成功会跳转至看板列表全部标签下，从url中获取status字段内容 */
        $form = $this->loadPage();
        $form->wait(3);
        $url = explode('status=', $this->response('url'));
        /* 根据url中是否包含status,判断是否创建成功 */
        if(!isset($url[1]))
        {
            if($this->checkFormTips($module)) return $this->success('创建看板表单页提示信息正确');
            return $this->failed('创建看板表单页提示信息不正确');
        }
        return $this->success('创建看板成功');
    }

    /**
     * 看板名称为空创建执行。
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function createWithEmptyName($execution)
    {
        $this->inputFields($execution);
        $form = $this->loadPage();
        $text = $form->dom->nameTip->getText();
        $info = sprintf($this->lang->error->notempty, $this->lang->kanban->name);

        if($text == $info) return $this->success('创建看板表单页提示信息正确');
        return $this->failed('创建看板表单页提示信息不正确');
    }

    /**
     * 看板名称已存在创建执行。
     * Create execution with repeat name.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function createWithRepeatName($execution)
    {
        $this->inputFields($execution);
        if($this->checkRepeatInfo()) return $this->success('创建看板表单页提示信息正确');
        return $this->failed('创建看板表单页提示信息不正确');
    }

    /**
     * 看板起止日期错误创建看板。
     * Create execution with date error.
     *
     * @param  array  $execution
     * @param  string $dateType   begin|end
     * @access public
     * @return object
     */
    public function createWithDateError($execution, $dateType = 'end')
    {
        $this->inputFields($execution);
        if($this->checkDateInfo($dateType)) return $this->success('创建看板表单页提示信息正确');
        return $this->failed('创建看板表单页提示信息不正确');
    }
}
