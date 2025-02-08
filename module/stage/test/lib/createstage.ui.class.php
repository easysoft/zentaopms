<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createStageTester extends tester
{
    /**
     * Create a stage.
     * 创建一个阶段。
     *
     * @param  array  $stage
     * @param  string $type  waterfall|waterfallplus
     * @access public
     * @return object
     */
    public function createStage(array $stage, string $type = '')
    {
        if($type == 'waterfall')
        {
            $form = $this->initForm('stage', 'browse', array(), 'appIframe-admin');
            $form->dom->createBtn->click();
        }
        if($type == 'waterfallplus')
        {
            $form = $this->initForm('stage', 'plusbrowse', array(), 'appIframe-admin');
            $form->dom->createBtn->click();
        }
        $createForm = $this->loadPage('stage', 'create');
        if(isset($stage['name']))    $createForm->dom->name->setValue($stage['name']);
        if(isset($stage['percent'])) $createForm->dom->percent->setValue($stage['percent']);
        if(isset($stage['type']))    $createForm->dom->type->picker($stage['type']);
        $createForm->dom->submitBtn->click();
        $createForm->wait(1);
}
