<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
