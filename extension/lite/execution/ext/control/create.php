<?php
helper::importControl('execution');
class myExecution extends execution
{
    public function create(int $projectID = 0, int $executionID = 0, int $copyExecutionID = 0, int $planID = 0, string $confirm = 'no', int $productID = 0, string $extra = '')
    {
        $this->config->execution->create->requiredFields = 'name,code,begin,end';
        $this->loadModel('project')->setMenu($projectID);
        parent::create($projectID, $executionID, $copyExecutionID, $planID, $confirm, $productID, $extra);
    }
}
