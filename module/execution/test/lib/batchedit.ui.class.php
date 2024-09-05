<?php
include dirname(__FILE__, 5) .'/test/lib/ui.php';
class batchEditTester extends tester
{
    /**
     * 批量编辑页面表单。
     * Batch edit page form.
     *
     * @param  array  $execution
     * @access public
     * @return string
     */
    public function inputFields($execution)
    {
        $form = $this->initForm('execution', 'all', '', 'appIframe-execution');
        $form->dom->firstCheckbox->click();
        $form->dom->btn($this->lang->edit)->click();

        $batchEditForm = $this->loadPage('execution', 'batchEdit');
        $id = $batchEditForm->dom->id_static_0->getText();
        $beginDom = "begin[{$id}]";
        $endDom   = "end[{$id}]";
        if(isset($execution['name']))  $batchEditForm->dom->name_0->setValue($execution['name']);
        if(isset($execution['begin'])) $batchEditForm->dom->$beginDom->datePicker($execution['begin']);
        if(isset($execution['end']))   $batchEditForm->dom->$endDom->datePicker($execution['end']);
        $batchEditForm->dom->submitBtn->click();
        $batchEditForm->wait(1);
        return $id;
    }

    /**
     * 检查执行名称字段。
     * Check execution name field.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function checkName($execution)
    {
        $this->inputFields($execution);
        $form = $this->loadPage();

        $text = $form->dom->alertModal('text');
        if($execution['name'] =='')
        {
            $info = sprintf($this->lang->error->notempty, $this->lang->execution->name);
            if($text == $info) return $this->success('执行名称为空提示信息正确');
            return $this->failed('执行名称为空提示信息不正确');
        }
        $info = sprintf($this->lang->error->repeat, $this->lang->execution->name, $execution['name']);
        if($text == $info) return $this->success('执行名称重复提示信息正确');
        return $this->failed('执行名称重复提示信息不正确');
    }
}
