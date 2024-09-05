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
    public function inputFields($execution)
    {
        $form = $this->initForm('execution', 'all', '', 'appIframe-execution');
        $form->dom->firstCheckbox->click();
        $form->dom->btn($this->lang->edit)->click();

        $batchEditForm = $this->loadPage('execution', 'batchEdit');
        $id = $batchEditForm->dom->id_static_0->getText();
        if(isset($execution['name']))  $batchEditForm->dom->name_0->setValue($execution['name']);
        if(isset($execution['begin'])) $batchEditForm->dom->begin[$id]->datePicker($execution['begin']);
        if(isset($execution['end']))   $batchEditForm->dom->end[$id]->datePicker($execution['end']);
        $batchEditForm->wait(1);
        $form->dom->btn($this->lang->save)->click();
    }
}
