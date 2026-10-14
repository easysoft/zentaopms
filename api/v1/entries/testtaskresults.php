<?php
require_once __DIR__ . '/testresults.php';
class testtaskresultsEntry extends testresultsEntry
{
    private function bindTask($taskID, $caseID)
    {
        $taskID = $this->positiveID($taskID);
        $caseID = $this->positiveID($caseID);
        if(!$taskID || !$caseID) throw EndResponseException::create($this->send400('Invalid testtask/case ID.'));
        $this->executionTask($taskID, $this->app->action === 'get' ? 'results' : 'runCase');
        $run = $this->caseRun($taskID, $caseID);
        if(!$run) throw EndResponseException::create($this->sendError(409, 'Case is not linked to this testtask.'));
        if(property_exists($this->requestBody, 'testtask') && $this->positiveID($this->requestBody->testtask) !== $taskID) throw EndResponseException::create($this->send400('Conflicting testtask ID.'));
        if(isset($_GET['testtask']) && $this->positiveID($_GET['testtask']) !== $taskID) throw EndResponseException::create($this->send400('Conflicting testtask ID.'));
        $this->requestBody->testtask = $taskID;
        $this->setParam('testtask', $taskID);
        $this->setParam('runID', (int)$run->id);
        return $caseID;
    }
    public function get($taskID = 0, $caseID = 0) { return parent::get($this->bindTask($taskID, $caseID)); }
    public function post($taskID = 0, $caseID = 0) { return parent::post($this->bindTask($taskID, $caseID)); }
}
