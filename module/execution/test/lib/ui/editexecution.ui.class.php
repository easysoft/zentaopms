<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
        $form = $this->initForm('execution', 'view', array('execution' => $execution['id']), 'appIframe-execution');
        $form->wait(3);
        $form->dom->editBtn->click();
        $form->wait(1);
        $form = $this->loadPage();
        $form->wait(1);
        if(isset($execution['name']))     $form->dom->name->setValue($execution['name']);
        if(isset($execution['begin']))    $form->dom->begin->datePicker($execution['begin']);
        if(isset($execution['end']))      $form->dom->end->datePicker($execution['end']);
        if(isset($execution['products'])) $form->dom->products->picker($execution['products']);
        $form->dom->submit->click();
        $form->wait(1);
        return $form;
    }

    /**
     * 执行名称已存在时获取提示信息。
     * Get error info of repeat name.
     *
     * @param  string $type sprint|stage|kanban
     * @access public
     * @return object
     */
    public function checkRepeatInfo($type = 'sprint')
    {
        $form = $this->loadPage();
        $form->wait(1);
        if(!is_object($form->dom->nameTip)) return $this->failed('执行名称重复没有提示信息');
        $text = $form->dom->nameTip->getText();
        if($type == 'kanban')
        {
            $info = sprintf($this->lang->error->repeat, $form->dom->nameLabel->getText(), $form->dom->name->getValue());
        }
        else
        {
            $info = sprintf($this->lang->error->repeat, $form->dom->nameLabel->getText(), $form->dom->name->getValue());
        }
        if($text != $info) return $this->failed('编辑执行表单页提示信息不正确');
        return $this->success('编辑执行表单页提示信息正确');
    }

    /**
     * 计划起止日期错误时获取提示信息。
     * Get error info of begin and end date.
     *
     * @param  string $dateType begin|end
     * @access public
     * @return object
     */
    public function checkDateInfo($dateType = 'end')
    {
        $form = $this->loadPage();
        $form->wait(1);

        if($dateType == 'begin')
        {
            if(!is_object($form->dom->beginTip)) return $thid->failed('开始日期错误没有提示信息');
            $text = $form->dom->beginTip->getText();
            $info = sprintf($this->lang->execution->errorCommonBegin, '');
        }
        else
        {
            if(!is_object($form->dom->endTip)) return $thid->failed('完成日期错误没有提示信息');
            $text = $form->dom->endTip->getText();
            $info = sprintf($this->lang->execution->errorCommonEnd, '');
        }

        /* 获取页面返回信息中除日期外的内容 */
        preg_match_all('/(\d{4}-\d{2}-\d{2})/', $text, $matches);
        $date   = $matches[0];
        $params = str_replace($date, '', $text);
        $params = trim($params);

        if($params != $info) return $this->failed('编辑执行表单页提示信息不正确');
        return $this->success('编辑执行表单页提示信息正确');
    }

    /**
     * 关联产品必填时获取提示信息。
     * Get error info of manage products.
     *
     * @access public
     * @return object
     */
    public function checkManageProductsInfo()
    {
        $form = $this->loadPage();
        $form->wait(1);
        $form->dom->waitElement($form->dom->xpath['productsTip'], 10);
        $text = $form->dom->productsTip->getText();
        $info = $this->lang->project->errorNoProducts;
        if($text != $info) return $this->failed('编辑执行表单页提示信息不正确');
        return $this->success('编辑执行表单页提示信息正确');
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
        if($this->checkFormTips('execution')) return $this->success('编辑执行表单页提示信息正确');
        /* 查看相关内容是否正确 */
        $viewPage = $this->loadPage('execution', 'view');
        $viewPage->wait(1);
        if(isset($execution['name']) && ($viewPage->dom->executionName->getText() != $execution['name']))        return $this->failed('编辑后执行名称错误');
        if(isset($execution['begin']) && ($viewPage->dom->plannedBegin->getText() != $execution['begin']))       return $this->failed('编辑后计划开始时间错误');
        if(isset($execution['end']) && ($viewPage->dom->plannedEnd->getText() != $execution['end']))             return $this->failed('编辑后计划完成时间错误');
        if(isset($execution['status']) && ($viewPage->dom->status->getText() != $execution['status']))           return $this->failed('编辑后状态错误');
        if(isset($execution['product']) && ($viewPage->dom->linckedProducta->getText() != $execution['products'])) return $this->failed('编辑后产品错误');
        return $this->success('编辑执行成功');
    }

    /**
     * 执行名称已存在编辑执行。
     * Edit execution with repeat name.
     *
     * @param  array  $execution
     * @param  string $type      sprint|stage|kanban
     * @access public
     * @return object
     */
    public function editWithRepeatName($execution, $type = 'sprint')
    {
        $this->editFields($execution);
        return $this->checkRepeatInfo($type);
    }

    /**
     * 执行起止日期错误编辑执行。
     * Edit execution with date error.
     *
     * @param  array  $execution
     * @param  string $dateType   begin|end
     * @access public
     * @return object
     */
    public function editWithDateError($execution, $dateType = 'end')
    {
        $this->editFields($execution);
        return $this->checkDateInfo($dateType);
    }

    /**
     * 执行关联产品必填，关联产品为空时编辑执行。
     * Edit execution with no manage products.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function editWithNoProducts($execution)
    {
        $this->editFields($execution);
        return $this->checkManageProductsInfo();
    }

}
