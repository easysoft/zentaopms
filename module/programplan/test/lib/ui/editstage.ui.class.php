<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class editStageTester extends tester
{
    /**
     * Check the page input when edit the stage.
     * 编辑阶段时检查页面输入。
     *
     * @param  array $waterfall
     * @access public
     * @return object
     */
    public function checkInput(array $waterfall)
    {
        $form = $this->initForm('project', 'execution' , array('status' => 'undone', 'projectID' => 60), 'appIframe-project');
        $form->dom->editBtn->click();
        $editForm = $this->loadPage('programplan', 'edit');

        if(isset($waterfall['name']))  $editForm->dom->name->setValue($waterfall['name']);
        if(isset($waterfall['begin'])) $editForm->dom->begin->setValue($waterfall['begin']);
        if(isset($waterfall['end']))   $editForm->dom->end->setValue($waterfall['end']);

        $editForm->wait(1);
        $editForm->dom->submitBtn->click();
        $editForm->wait(1);
        return $this->checkResult($editForm, $waterfall);
    }

    /**
     * Check the result after edit the stage.
     * 编辑阶段后检查结果。
     *
     * @param  array $waterfall
     * @access public
     * @return object
     */
    public function checkResult(object $editForm, array $waterfall)
    {
        if($this->response('module') == 'project')
        {
            if($editForm->dom->nameTip)
            {
                //检查阶段名称不能为空
                $nameTipText = $editForm->dom->nameTip->getText();
                $nameTip     = sprintf($this->lang->error->notempty, $this->lang->programplan->name);
                return ($nameTipText == $nameTip) ? $this->success('编辑阶段表单页提示信息正确') : $this->failed('编辑阶段表单页提示信息不正确');
            }
            if($editForm->dom->beginTip)
            {
                //检查计划开始日期不能为空
                $beginTipText = $editForm->dom->beginTip->getText();
                $beginTip     = sprintf($this->lang->error->notempty, $this->lang->programplan->begin);
                return ($beginTipText == $beginTip) ? $this->success('编辑阶段表单页提示信息正确') : $this->failed('编辑阶段表单页提示信息不正确');
            }
            if($editForm->dom->endTip)
            {
                //检查计划结束日期不能为空
                $endTipText = $editForm->dom->endTip->getText();
                $endTip     = sprintf($this->lang->error->notempty, $this->lang->programplan->end);
                return ($endTipText == $endTip) ? $this->success('编辑阶段表单页提示信息正确') : $this->failed('编辑阶段表单页提示信息不正确');
            }
        }

        $executionPage = $this->loadPage('project', 'execution');
        if($executionPage->dom->name->getText()  != $waterfall['name']) return $this->failed('阶段名称错误');
        if($executionPage->dom->begin->getText() != $waterfall['begin']) return $this->failed('阶段计划开始错误');
        if($executionPage->dom->end->getText()   != $waterfall['end'])  return $this->failed('阶段计划完成错误');
        return $this->success('编辑阶段成功');
    }
}
