<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editStageTester extends tester
{
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
}
