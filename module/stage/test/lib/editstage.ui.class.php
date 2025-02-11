<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editStageTester extends tester
{
    /**
     * Edit a stage.
     * 编辑一个阶段。
     *
     * @param  array  $stage
     * @param  string $type  waterfall|waterfallplus
     * @access public
     * @return object
     */
    public function editStage(array $stage, string $type = '')
    {
        if($type == 'waterfall')
        {
            $form = $this->initForm('stage', 'browse', array(), 'appIframe-admin');
            $form->dom->editBtn->click();
        }
        if($type == 'waterfallplus')
        {
            $form = $this->initForm('stage', 'plusbrowse', array(), 'appIframe-admin');
            $form->dom->plusEditBtn->click();
        }
        $editForm = $this->loadPage('stage', 'edit');
        if(isset($stage['name']))    $editForm->dom->name->setValue($stage['name']);
        if(isset($stage['percent'])) $editForm->dom->percent->setValue($stage['percent']);
        if(isset($stage['type']))    $editForm->dom->type->picker($stage['type']);
        if($type == 'waterfall')
        {
            $editForm->dom->submitBtn->click();
        }
        else
        {
            $editForm->dom->plusSubmitBtn->click();
        }
        $editForm->wait(1);

        /* 检查阶段名称和工作量占比不能为空。 */
        if($editForm->dom->nameTip && $editForm->dom->percentTip)
        {
            $nameTipText    = $editForm->dom->nameTip->getText();
            $nameTip        = sprintf($this->lang->error->notempty, $this->lang->stage->name);
            $percentTipText = $editForm->dom->percentTip->getText();
            $percentTip     = sprintf($this->lang->error->notempty, $this->lang->stage->percent);
            return ($nameTipText == $nameTip && $percentTipText == $percentTip) ? $this->success('编辑阶段表单页提示信息正确') : $this->failed('编辑阶段表单页提示信息不正确');
        }
        /* 检查工作量占比累计不能超过100% */
        if($editForm->dom->percentOverTip)
        {
            $percentOverText = $editForm->dom->percentOverTip->getText();
            $percentOverTip  = $this->lang->stage->error->percentOver;
            return ($percentOverText == $percentOverTip) ? $this->success('工作量占比累计超出100%时提示信息正确') : $this->failed('工作量占比累计超出100%时提示信息不正确');
        }
