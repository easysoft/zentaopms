<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class batchCreateStageTester extends tester
{
    /**
     * Batch create a stage.
     * 批量创建一个阶段。
     *
     * @param  array  $stage
     * @access public
     * @return object
     */
    public function batchCreateStage(array $stage)
    {
        $form = $this->initForm('stage', 'browse', array(), 'appIframe-admin');
        $form->dom->batchCreateBtn->click();
        $batchCreateForm = $this->loadpage('stage', 'batchcreate');
        $batchCreateForm->wait(1);
        if(isset($stage['name'])) $batchCreateForm->dom->name->setValue($stage['name']);
        if(isset($stage['type'])) $batchCreateForm->dom->type->picker($stage['type']);
        $batchCreateForm->dom->submitBtn->click();
        $batchCreateForm->wait(1);

        /* 跳转到阶段列表，检查阶段信息。*/
        $browsePage = $this->loadPage('stage', 'browse');
        if($browsePage->dom->stageName->gettext() != $stage['name']) return $this->failed('阶段名称错误');
        if($browsePage->dom->stageType->gettext() != $stage['type']) return $this->failed('阶段类型错误');

        return $this->success('批量新建阶段成功');
    }
}
