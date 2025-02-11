<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editCaselibTester extends tester
{
    /**
     * 编辑用例库
     * Edit caselib.
     *
     * @param  array $caselib
     * @access public
     */
    public function editCaselib($caselib)
    {
        $form = $this->initForm('caselib', 'browse', array('libID' => 1), 'appIframe-qa');
        $form->dom->btn($this->lang->caselib->view)->click();
        $form->dom->editBtn->click();
        if(isset($caselib['name'])) $form->dom->name->setValue($caselib['name']);

        $form->dom->btn($this->lang->save)->click();
