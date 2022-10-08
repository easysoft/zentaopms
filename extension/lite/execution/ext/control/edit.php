<?php
helper::importControl('execution');
class myExecution extends execution
{
    public function edit($executionID, $action = 'edit', $extra = '', $newPlans = '', $confirm = 'no')
    {
        $this->config->execution->edit->requiredFields  = 'name,code,begin,end';
        parent::edit($executionID, $action, $extra);
    }
}
