<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editDesignTester extends tester
{
    /**
     * Edit a design.
     * 编辑设计输入表单。
     *
     * @param  array  $design
     * @access public
     * @return object
     */
    public function editDesign(array $design)
    {
        $form = $this->initForm('design', 'edit', array('designID' => 1), 'appIframe-project');
        if(isset($design['product'])) $form->dom->type->setValue($design['product']);
        if(isset($design['type']))    $form->dom->type->setValue($design['type']);
        if(isset($design['name']))    $form->dom->name->setValue($design['name']);

        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        return $this->checkResult($design);
    }
