<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createStageTester extends tester
{
    public function createStage(array $waterfall)
    {
        $programplanForm = $this->initForm('programplan', 'create' , array('projectID' => 5), 'appIframe-project');

        //删除除需求外的其他默认阶段行
        for ($i = 6; $i > 1; $i--)
        {
            $btn = "deleteBtn_$i";
            $programplanForm->dom->$btn->click();
            $programplanForm->wait(1);
        }
