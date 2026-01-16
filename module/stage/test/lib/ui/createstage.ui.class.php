<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createStageTester extends tester
{
    /**
     * Create a stage.
     * 创建一个阶段。
     *
     * @param  array  $stage
     * @access public
     * @return object
     */
    public function createStage(array $stage)
    {
        $form = $this->initForm('stage', 'browse', array(), 'appIframe-admin');
        $form->dom->createBtn->click();
        $createForm = $this->loadPage('stage', 'create');
        if(isset($stage['name'])) $createForm->dom->name->setValue($stage['name']);
        if(isset($stage['type'])) $createForm->dom->type->picker($stage['type']);
        $createForm->dom->submitBtn->click();
        $createForm->wait(1);

        /* 检查阶段名称不能为空 */
        if($createForm->dom->nameTip)
        {
            $nameTipText = $createForm->dom->nameTip->getText();
            $nameTip     = sprintf($this->lang->error->notempty, $this->lang->stage->name);
            return ($nameTipText == $nameTip) ? $this->success('新建阶段表单页提示信息正确') : $this->failed('新建阶段表单页提示信息不正确');
        }

        /* 跳转到阶段列表，检查阶段信息。 */
        $browsePage = $this->loadPage('stage', 'browse');
        $browsePage->wait(1);
        if($browsePage->dom->stageName->getText() != $stage['name']) return $this->failed('阶段名称错误');
        if($browsePage->dom->stageType->getText() != $stage['type']) return $this->failed('阶段类型错误');

        return $this->success('新建阶段成功');
    }
}
