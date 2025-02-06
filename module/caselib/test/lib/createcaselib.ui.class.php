<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createCaselibTester extends tester
{
    /**
     * 创建用例库
     * Create caselib.
     *
     * @param  array $caselib
     * @param  $libID 已有的用例库ID
     * @access public
     */
    public function createCaselib($caselib, $libID = null)
    {
        $form = $this->initForm('caselib', 'create', $libID, 'appIframe-qa');
        if($libID) $form->dom->btn($this->lang->caselib->create)->click();

        if(isset($caselib['name'])) $form->dom->name->setValue($caselib['name']);

        $form->dom->btn($this->lang->save)->click();
