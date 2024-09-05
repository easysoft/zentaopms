<?php
include dirname(__FILE__, 5) .'/test/lib/ui.php';
class batchEditTester extends tester
{
    /**
     * 批量编辑页面表单。
     * Batch edit page form.
     *
     * @param array $execution
     * @access public
     */
    public function inputForm($execution)
    {
        $form = $this->initForm('execution', 'all', '', 'appIframe-execution');
        $form->dom->firstCheckbox->click();
        $form->dom->btn($this->lang->edit)->click();

        $form = $this->loadPage('execution', 'batchEdit');
        $id = $form->dom->id_static_0->getValue();
        if(isset($execution['name']))  $form->dom->name_0->setValue($execution['name']);
        if(isset($execution['begin'])) $form->dom->begin[{$id}]->datePicker($execution['begin']);
        if(isset($execution['end']))   $form->dom->end[{$id}]->datePicker($execution['end']);
        $form->dom->btn($this->lang->save)->click();
    }
}
