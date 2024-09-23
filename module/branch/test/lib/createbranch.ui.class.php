<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createBranchTester extends tester
{
    public function createBranch($branch, $branchurl)
    {
        $form = $this->initForm('branch', 'manage', $branchurl, 'appIframe-product');
        $form->dom->btn($this->lang->branch->create)->click();
        if (isset($branch->name)) $form->dom->name->setValue($branch->name);
        if (isset($branch->desc)) $form->dom->desc->setValue($branch->desc);
        $form->dom->save->click();
    }
}
