<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class editStageTester extends tester
{
    /**
     * Edit a stage.
     * 编辑一个阶段。
     *
     * @param  array  $stage
     * @access public
     * @return object
     */
    public function editStage(array $stage)
    {
        $form = $this->initForm('stage', 'browse', array(), 'appIframe-admin');
        $form->dom->editBtn->click();
        $editForm = $this->loadPage('stage', 'edit');
        if(isset($stage['name'])) $editForm->dom->name->setValue($stage['name']);
        if(isset($stage['type'])) $editForm->dom->type->picker($stage['type']);
        $editForm->dom->submitBtn->click();
        $editForm->wait(1);

        /* 检查阶段名称不能为空。 */
        if($editForm->dom->nameTip)
        {
            $nameTipText = $editForm->dom->nameTip->getText();
            $nameTip     = sprintf($this->lang->error->notempty, $this->lang->stage->name);
            return ($nameTipText == $nameTip) ? $this->success('编辑阶段表单页提示信息正确') : $this->failed('编辑阶段表单页提示信息不正确');
        }
        /* 跳转到阶段列表，检查阶段信息。 */
        $browsePage = $this->loadPage('stage', 'browse');
        if($browsePage->dom->stageNameA->getText() != $stage['name']) return $this->failed('阶段名称错误');
        if($browsePage->dom->stageTypeA->getText() != $stage['type']) return $this->failed('阶段类型错误');

        return $this->success('编辑阶段成功');
    }
}
