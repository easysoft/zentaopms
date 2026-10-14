<?php
require_once __DIR__ . '/testtaskexecution.php';
class testtaskcasesEntry extends testtaskExecutionEntry
{
    public function get($taskID)
    {
        $taskID = $this->positiveID($taskID);
        if(!$taskID) return $this->send400('Invalid testtask ID.');
        $this->executionTask($taskID, 'cases');
        $limit = $this->positiveID($this->param('limit', 20));
        $page  = $this->positiveID($this->param('page', 1));
        if(!$limit || $limit > 100 || !$page) return $this->send400('Invalid pagination.');
        $total = (int)$this->dao->select('COUNT(*) AS count')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetch('count');
        $runs = $this->dao->select('*')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->orderBy('id_asc')->limit(($page - 1) * $limit, $limit)->fetchAll();
        return $this->send(200, array('testtask' => $taskID, 'total' => $total, 'page' => $page, 'limit' => $limit, 'runs' => array_values($runs)));
    }
    public function post($taskID)
    {
        $taskID = $this->positiveID($taskID);
        if(!$taskID) return $this->send400('Invalid testtask ID.');
        $task = $this->executionTask($taskID, 'linkCase');
        if(in_array($task->status, array('done', 'closed'))) return $this->sendError(409, 'Testtask is closed.');
        $items = $this->request('cases');
        if(!is_array($items) || !$items || count($items) > 100) return $this->send400('Provide 1 to 100 cases.');
        $runs = array();
        foreach($items as $item)
        {
            if(!is_object($item)) return $this->send400('Invalid case.');
            $caseID = $this->positiveID($item->case ?? 0);
            $version = $this->positiveID($item->version ?? 0);
            if(!$caseID || !$version || isset($runs[$caseID])) return $this->send400('Invalid or duplicate case/version.');
            $case = $this->loadModel('testcase')->getByID($caseID, $version);
            if(!$case || !empty($case->deleted)) return $this->send404();
            if((int)$case->product !== (int)$task->product) return $this->send400('Case must belong to the testtask product.');
            if(!$this->app->checkObjectPriv($case, TABLE_CASE)) return $this->sendError(403, 'Case is not allowed.');
            if(($version > (int)$case->version || empty($case->steps))) return $this->sendError(409, 'Case version does not exist.');
            if(!empty($case->branch) && !in_array((string)$case->branch, explode(',', (string)$task->branch), true)) return $this->send400('Case branch is not included in the testtask.');
            $runs[$caseID] = (object)array('case' => $caseID, 'version' => $version);
        }
        $this->dao->begin();
        try
        {
            /* Serialize associations for this task, preserving existing run identity and results. */
            $lock = $this->dao->dbh->prepare('SELECT id FROM ' . TABLE_TESTTASK . ' WHERE id = ? FOR UPDATE');
            $lock->execute(array($taskID));
            $new = array();
            foreach($runs as $caseID => $run)
            {
                $existing = $this->caseRun($taskID, $caseID);
                if($existing && (int)$existing->version !== $run->version)
                {
                    $this->dao->rollback();
                    return $this->sendError(409, 'Case is already linked with a different version.');
                }
                if(!$existing) $new[] = $run;
            }
            $this->app->session->set('product', (int)$task->product);
            if($new && !$this->loadModel('testtask')->linkCase($taskID, 'all', $new))
            {
                $this->dao->rollback();
                return $this->send400('Unable to link cases.');
            }
            $this->dao->commit();
        }
        catch(Throwable $error) { $this->dao->rollback(); throw $error; }
        $result = array();
        foreach($runs as $caseID => $run) $result[] = $this->caseRun($taskID, $caseID);
        return $this->send(200, array('testtask' => $taskID, 'runs' => $result));
    }
}
