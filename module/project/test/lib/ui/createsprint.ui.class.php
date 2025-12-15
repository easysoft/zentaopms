<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createSprintTester extends tester
{
    /**
     * Check the page input when creating the sprint.
     * 添加迭代时检查页面输入
     *
     * @param  array $sprint
     * @access public
     */
    public function checkInput(array $sprint)
    {
        $sprintForm = $this->initForm('project', 'execution', array('status' => 'undone', 'projectID' => '1'), 'appIframe-project');
        $sprintForm->dom->addSprint->click();
        $form = $this->initForm('execution', 'create', array('projectID' => '1'));
        if(isset($sprint['name']))    $form->dom->name->setValue($sprint['name']);
        if(isset($sprint['project'])) $form->dom->project->picker($sprint['project']);
        if(isset($sprint['end']))     $form->dom->end->datepicker($sprint['end']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(5);
        return $this->checkResult($sprint);
    }

    /**
     * Check the result after creating the sprint.
     *
     * @param  string $scrum
     * @access public
     * @return object
     */
    public function checkResult($sprint)
    {
        //检查添加页面时的提示信息
        if(strpos($this->response('url'), 'executionID') === false)
        {
            if($this->checkFormTips('execution')) return $this->success('添加迭代表单页提示信息正确');
            if($form->dom->endTip)
            {
                //检查结束日期不能为空
                $endTiptext = $form->dom->endTip->getText();
                $endTip     = sprintf($this->lang->copyProject->endTip, '');
                return ($endTiptext == $endTip) ? $this->success('添加迭代表单页提示信息正确') : $this->failed('添加迭代表单页提示信息不正确');
                $form->wait(2);
            }
            return $this->failed('添加迭代表单页提示信息不正确');
        }
        //检查添加成功后的断言
        else
        {
            $sprintForm = $this->initForm('project', 'execution', array('status' => 'undone', 'projectID' => '1'), 'appIframe-project');
            /* 跳转到项目迭代列表页面，查看列表中的迭代信息是否正确*/
            $browsePage = $this->loadPage('project', 'execution');
            $browsePage->wait(2);

            //断言检查名称、项目类型是否正确
            if($browsePage->dom->sprintName->getText() != $sprint['name']) return $this->failed('名称错误');
            $browsePage->dom->planEnd->scrollToElement();
            if($browsePage->dom->planEnd->getText() != $sprint['end'])     return $this->failed('名称错误');

            return $this->success('添加迭代成功');
        }
    }
}
