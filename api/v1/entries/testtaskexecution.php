<?php
/** Shared boundary for native test execution APIs; no direct business writes. */
class testtaskExecutionEntry extends entry
{
    public function __construct()
    {
        parent::__construct();
        if(!is_object($this->requestBody)) throw EndResponseException::create($this->send400('A JSON object is required.'));
    }
    public function put($taskID = 0, $caseID = 0) { return $this->sendError(405, 'Method not allowed.'); }
    public function delete($taskID = 0, $caseID = 0) { return $this->sendError(405, 'Method not allowed.'); }
    protected function positiveID($value): int
    {
        if(!is_int($value) && !(is_string($value) && ctype_digit($value))) return 0;
        return filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1))) ?: 0;
    }
    protected function executionTask(int $taskID, string $method): object
    {
        if(!commonModel::hasPriv('testtask', $method)) throw EndResponseException::create($this->sendError(403, 'Access not allowed.'));
        $task = $this->loadModel('testtask')->getByID($taskID);
        if(!$task || !empty($task->deleted)) throw EndResponseException::create($this->send404());
        if(!$this->app->checkObjectPriv($task, TABLE_TESTTASK)) throw EndResponseException::create($this->sendError(403, 'Testtask is not allowed.'));
        return $task;
    }
    protected function caseRun(int $taskID, int $caseID)
    {
        return $this->dao->select('*')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->andWhere('`case`')->eq($caseID)->fetch();
    }
}
