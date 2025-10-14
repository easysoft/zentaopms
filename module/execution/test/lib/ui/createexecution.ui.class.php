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
        $form = $this->initForm('execution', 'create', '', 'appIframe-execution');
        $form->wait(1);
        if(isset($execution['project'])) $form->dom->project->picker($execution['project']);
        $form = $this->loadPage();
        $form->wait(1);
        if(isset($execution['name']))     $form->dom->name->setValue($execution['name']);
        if(isset($execution['begin']))    $form->dom->begin->datePicker($execution['begin']);
        if(isset($execution['end']))      $form->dom->end->datePicker($execution['end']);
        if(isset($execution['products'])) $form->dom->products->picker($execution['products']);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(3);
    }

    /**
     * 执行名称已存在时获取提示信息。
     * Get error info of repeat name.
     *
     * @param  string $type sprint|stage|kanban
     * @access public
     * @return bool
     */
    public function checkRepeatInfo($type = 'sprint')
    {
        $form = $this->loadPage();
        $form->wait(1);
        $text = $form->dom->nameTip->getText();
        if($type == 'kanban')
        {
            $info = sprintf($this->lang->error->repeat, $this->lang->kanban->name, $form->dom->name->getValue());
        }
        else
        {
            $info = sprintf($this->lang->error->repeat, $this->lang->execution->execName, $form->dom->name->getValue());
        }

        if($text == $info) return true;
        return false;
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
            $info = sprintf($this->lang->execution->errorCommonBegin, '');
        }
        else
        {
            $text = $form->dom->endTip->getText();
            $info = sprintf($this->lang->execution->errorCommonEnd, '');
        }

        /* 获取页面返回信息中除日期外的内容 */
        preg_match_all('/(\d{4}-\d{2}-\d{2})/', $text, $matches);
        $date   = $matches[0];
        $params = str_replace($date, '', $text);
        $params = trim($params);

        if($params == $info) return true;
        return false;
    }

    /**
     * 关联产品必填时获取提示信息。
     * Get error info of manage products.
     *
     * @access public
     * @return bool
     */
    public function checkManageProductsInfo()
    {
        $form = $this->loadPage();
        $form->dom->waitElement($form->dom->xpath['productsTip'], 10);
        $text = $form->dom->productsTip->getText();
        $info = $this->lang->project->errorNoProducts;
        if($text == $info) return true;
        return false;
    }

    /**
     * 创建执行。
     * Create a new execution .
     *
     * @param  array  $execution
     * @param  string $type      sprint|stage|kanban
     * @access public
     * @return object
     */
    public function create($execution, $type = 'sprint')
    {
        $this->inputFields($execution);

        /* 从url中获取executionID */
        $url = explode('executionID=', $this->response('url'));
        /* 根据url中是否包含executionID,判断是否创建成功 */
        if(!isset($url[1]))
        {
            if($this->checkFormTips('execution')) return $this->success('创建执行表单页提示信息正确');
            return $this->failed('创建执行表单页提示信息不正确');
        }
        /* 跳转至概况页 */
        $viewPage = $this->initForm('execution', 'view', array('executionID' => $url[1]), 'appIframe-execution');

        /* 校验执行信息 */
        if($viewPage->dom->executionName->getText() != $execution['name'])                return $this->failed('执行名称错误');
        if($viewPage->dom->status->getText() != $this->lang->execution->statusList->wait) return $this->failed('执行状态错误');
        if($viewPage->dom->acl->getText() != $this->lang->execution->kanbanAclList->open) return $this->failed('执行权限错误');
        if($viewPage->dom->plannedBegin->getText() != $execution['begin'])                return $this->failed('计划开始时间错误');
        if($viewPage->dom->plannedEnd->getText() != $execution['end'])                    return $this->failed('计划完成时间错误');
        return $this->success('创建执行成功');
    }

    /**
     * 执行名称已存在创建执行。
     * Create execution with repeat name.
     *
     * @param  array  $execution
     * @param  string $type      sprint|stage|kanban
     * @access public
     * @return object
     */
    public function createWithRepeatName($execution, $type = 'sprint')
    {
        $this->inputFields($execution);
        if($this->checkRepeatInfo($type)) return $this->success('创建执行表单页提示信息正确');
        return $this->failed('创建执行表单页提示信息不正确');
    }

    /**
     * 执行起止日期错误创建执行。
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
        if($this->checkDateInfo($dateType)) return $this->success('创建执行表单页提示信息正确');
        return $this->failed('创建执行表单页提示信息不正确');
    }

    /**
     * 执行关联产品必填，关联产品为空时创建执行。
     * Create execution with no manage products.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function createWithNoProducts($execution)
    {
        $this->inputFields($execution);
        if($this->checkManageProductsInfo()) return $this->success('创建执行表单页提示信息正确');
        return $this->failed('创建执行表单页提示信息不正确');
    }
}
