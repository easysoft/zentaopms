<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createStageTester extends tester
{
    /**
     * Create a stage.
     * 创建一个阶段。
     *
     * @param  array  $stage
     * @param  string $type  waterfall|waterfallplus
     * @access public
     * @return object
     */
    public function createStage(array $stage, string $type = '')
    {
        if($type == 'waterfall')
        {
            $form = $this->initForm('stage', 'browse', array(), 'appIframe-admin');
            $form->dom->createBtn->click();
        }
        if($type == 'waterfallplus')
        {
            $form = $this->initForm('stage', 'plusbrowse', array(), 'appIframe-admin');
            $form->dom->createBtn->click();
        }
        $createForm = $this->loadPage('stage', 'create');
        if(isset($stage['name']))    $createForm->dom->name->setValue($stage['name']);
        if(isset($stage['percent'])) $createForm->dom->percent->setValue($stage['percent']);
        if(isset($stage['type']))    $createForm->dom->type->picker($stage['type']);
        $createForm->dom->submitBtn->click();
        $createForm->wait(1);

        /* 检查阶段名称和工作量占比不能为空 */
        if($createForm->dom->nameTip && $createForm->dom->percentTip)
        {
            $nameTipText    = $createForm->dom->nameTip->getText();
            $nameTip        = sprintf($this->lang->error->notempty, $this->lang->stage->name);
            $percentTipText = $createForm->dom->percentTip->getText();
            $percentTip     = sprintf($this->lang->error->notempty, $this->lang->stage->percent);
            return ($nameTipText == $nameTip && $percentTipText == $percentTip) ? $this->success('新建阶段表单页提示信息正确') : $this->failed('新建阶段表单页提示信息不正确');
        }
        /* 检查工作量占比累计不能超过100% */
        if($createForm->dom->percentOverTip)
        {
            $percentOverText = $createForm->dom->percentOverTip->getText();
            $percentOverTip  = $this->lang->stage->error->percentOver;
            return ($percentOverText == $percentOverTip) ? $this->success('工作量占比累计超出100%时提示信息正确') : $this->failed('工作量占比累计超出100%时提示信息不正确');
        }
}
