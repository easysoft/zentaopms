<?php
helper::importControl('execution');
class myExecution extends execution
{
    public function create($projectID = '', $executionID = 0, $copyExecutionID = '', $planID = 0, $confirm = 'no', $productID = 0, $extra = '')
    {
        $this->config->execution->create->requiredFields = 'name,code,begin,end';
        $this->loadModel('project')->setMenu($projectID);
        parent::create($projectID, $executionID, $copyExecutionID, $planID, $confirm, $productID, $extra);
    }
}
