<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createSubStageTester extends tester
{
    public function createSubStage(array $waterfall)
    {
        $programplanForm = $this->initForm('programplan', 'create' , array('projectID' => 60, 'productID' => 0, 'executionID' => 106), 'appIframe-project');

        if(isset($waterfall['name_0']))  $programplanForm->dom->name_0->setValue($waterfall['name_0']);
        if(isset($waterfall['begin_0'])) $programplanForm->dom->begin->setValue($waterfall['begin_0']);
        if(isset($waterfall['end_0']))   $programplanForm->dom->end->setValue($waterfall['end_0']);
        $programplanForm->wait(1);
        $programplanForm->dom->submitBtn->click();
        $programplanForm->wait(1);
