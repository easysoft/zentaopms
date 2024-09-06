<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDesignTester extends tester
{
    public function createDesign(array $design)
    {
        $form = $this->initForm('design', 'create', array('projecID' => 60, 'productID' => 0), 'appIframe-project');
        if(isset($design['product'])) $form->dom->type->setValue($design['product']);
        if(isset($design['type']))    $form->dom->type->setValue($design['type']);
        if(isset($design['name']))    $form->dom->name->setValue($design['name']);

        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        return $this->checkResult($design);
    }
