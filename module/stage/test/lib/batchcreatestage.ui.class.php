<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchCreateStageTester extends tester
{
    /**
     * Batch create a stage.
     * 批量创建一个阶段。
     *
     * @param  array  $stage
     * @param  string $type  waterfall|waterfallplus
     * @access public
     * @return object
     */
    public function batchCreateStage(array $stage, string $type = '')
    {
        if($type == 'waterfall')
        {
            $form = $this->initForm('stage', 'browse', array(), 'appIframe-admin');
            $form->dom->batchCreateBtn->click();
        }
        if($type == 'waterfallplus')
        {
            $form = $this->initform('stage', 'plusbrowse', array(), 'appiframe-admin');
            $form->dom->batchcreatebtn->click();
        }
        $batchcreateform = $this->loadpage('stage', 'batchcreate');
        if(isset($stage['name']))    $batchcreateform->dom->name->setvalue($stage['name']);
        if(isset($stage['percent'])) $batchcreateform->dom->percent->setvalue($stage['percent']);
        if(isset($stage['type']))    $batchcreateform->dom->type->select('text', $stage['type']);
        $batchcreateform->dom->submitbtn->click();
        $batchcreateform->wait(1);
}
