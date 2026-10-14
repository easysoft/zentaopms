<?php
/**
 * The testresults entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
require_once __DIR__ . '/testtaskexecution.php';
class testresultsEntry extends testtaskExecutionEntry
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function get($caseID = 0)
    {
        if(!$caseID) return $this->sendError(400, 'Need case id.');
        $caseID = $this->positiveID($caseID);
        if(!$caseID) return $this->send400('Invalid case ID.');
        $case = $this->loadModel('testcase')->getByID($caseID);
        if(!$case || !empty($case->deleted)) return $this->send404();
        if(!$this->app->checkObjectPriv($case, TABLE_CASE)) return $this->sendError(403, 'Case is not allowed.');
        $version = $this->param('version', 0);
        $runID   = $this->param('runID', 0);
        if($version != 0 && !$this->positiveID($version)) return $this->send400('Invalid version.');
        if($runID != 0)
        {
            $runID = $this->positiveID($runID);
            if(!$runID) return $this->send400('Invalid run ID.');
            $run = $this->dao->select('*')->from(TABLE_TESTRUN)->where('id')->eq($runID)->fetch();
            if(!$run || (int)$run->case !== $caseID) return $this->send404();
            $this->executionTask((int)$run->task, 'results');
        }

        $control = $this->loadController('testtask', 'results');
        $control->results((int)$runID, (int)$caseID, (int)$version);

        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $results = array();
            foreach($data->data->results as $result)
            {
                $result->stepResults = array_values((array)$result->stepResults);
                $results[] = $result;
            }

            return $this->send(200, array('results' => $results));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @param  int    $caseID
     * @access public
     * @return string
     */
    public function post($caseID = 0)
    {
        if(!$caseID) $caseID = $this->param('case');
        if(!$caseID) return $this->sendError(400, 'Need case id.');

        $control = $this->loadController('testtask', 'runCase');

        $caseID = $this->positiveID($caseID);
        $case = $this->loadModel('testcase')->getByID($caseID);
        if(!$case || !empty($case->deleted)) return $this->send404();
        if(!$this->app->checkObjectPriv($case, TABLE_CASE)) return $this->sendError(403, 'Case is not allowed.');
        if(property_exists($this->requestBody, 'testtask') && $this->requestBody->testtask === null) return $this->send400('Invalid testtask ID.');
        if(property_exists($this->requestBody, 'version') && $this->requestBody->version === null) return $this->send400('Invalid version.');
        $taskValue = $this->request('testtask', $this->param('testtask', 0));
        if(isset($this->requestBody->testtask) && isset($_GET['testtask']) && (string)$taskValue !== (string)$_GET['testtask']) return $this->send400('Conflicting testtask IDs.');
        $taskID = $taskValue === 0 || $taskValue === '0' ? 0 : $this->positiveID($taskValue);
        if(!$taskID && $taskValue !== 0 && $taskValue !== '0') return $this->send400('Invalid testtask ID.');
        $run = false;
        if($taskID)
        {
            $task = $this->executionTask($taskID, 'runCase');
            if(in_array($task->status, array('done', 'closed'))) return $this->sendError(409, 'Testtask is closed.');
            $run = $this->caseRun($taskID, $caseID);
            if(!$run) return $this->sendError(409, 'Case is not linked to this testtask.');
            if((int)$case->product !== (int)$task->product) return $this->send400('Case product mismatch.');
        }
        $versionValue = $this->request('version', $this->param('version', $run ? (int)$run->version : (int)$case->version));
        if(isset($this->requestBody->version) && isset($_GET['version']) && (string)$versionValue !== (string)$_GET['version']) return $this->send400('Conflicting versions.');
        $version = $this->positiveID($versionValue);
        if(!$version) return $this->send400('Invalid version.');
        if($run && (int)$run->version !== $version) return $this->sendError(409, 'Result version must match linked run.');
        $versioned = $this->loadModel('testcase')->getByID($caseID, $version);
        if(!$versioned || ($version > (int)$versioned->version || empty($versioned->steps))) return $this->sendError(409, 'Case version does not exist.');
        $steps = array();
        foreach($versioned->steps as $step) if($step->type !== 'group') $steps[] = $step->id;
        $runID = $run ? (int)$run->id : 0;
        if(!isset($this->requestBody->steps) || !is_array($this->requestBody->steps) || count($this->requestBody->steps) !== count($steps) || !$steps) return $this->send400('Provide exactly one result per executable step.');
        foreach($this->requestBody->steps as $step)
        {
            if(!is_object($step) || !isset($step->result, $step->real) || !in_array($step->result, array('pass', 'fail', 'blocked', 'n/a'), true) || !is_string($step->real)) return $this->send400('Invalid step result.');
        }

        $this->setPost('case',  $caseID);
        $this->setPost('version', $version);

        /* Set steps and expects. */
        if(isset($this->requestBody->steps))
        {
            $results = array();
            $reals   = array();
            foreach($this->requestBody->steps as $index => $step)
            {
                $stepID = $steps[$index];
                $results[$stepID] = $step->result;
                $reals[$stepID] = $step->real;
            }
            $this->setPost('result',  $results);
            $this->setPost('real',  $reals);
        }

        $this->dao->begin();
        try
        {
            /* Serialize result writes for this case so the returned ID belongs to this write. */
            $lock = $this->dao->dbh->prepare('SELECT id FROM ' . TABLE_CASE . ' WHERE id = ? FOR UPDATE');
            $lock->execute(array($caseID));
            $control->testtask->lastCreatedResultID = 0;
            $control->runCase((int)$runID, (int)$caseID, (int)$version);
            $data = $this->getData();
            if(isset($data->result) && $data->result === 'fail')
            {
                $this->dao->rollback();
                return $this->sendError(400, $data->message);
            }
            $result = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('id')->eq((int)$control->testtask->lastCreatedResultID)->andWhere('run')->eq($runID)->andWhere('`case`')->eq($caseID)->andWhere('version')->eq($version)->andWhere('lastRunner')->eq($this->app->user->account)->orderBy('id_desc')->fetch();
            if(!$result) { $this->dao->rollback(); return $this->send400('Result was not saved.'); }
            $this->dao->commit();
        }
        catch(Throwable $error) { $this->dao->rollback(); throw $error; }
        return $this->send(200, array('status' => 'success', 'resultID' => (int)$result->id, 'runID' => $runID, 'testtask' => $taskID, 'case' => $caseID, 'version' => $version));
    }

    /**
     * Get steps key.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function getStepIDList($taskID, $caseID, $version)
    {
        if($taskID)
        {
            $run       = $this->loadModel('testtask')->getRunByCase($taskID, $caseID);
            $run->case = $this->loadModel('testcase')->getById($caseID, $version);
        }
        else
        {
            $run = new stdclass();
            $run->id   = 0;
            $run->case = $this->loadModel('testcase')->getById($caseID, $version);
        }

        $caseIDList = array();
        foreach($run->case->steps as $key => $step)
        {
            /* Unset step if step is a group. */
            if($step->type == 'group')
            {
                unset($run->case->steps[$key]);
            }
            $caseIDList[] = $step->id;
        }

        return array($run->id, $caseIDList);
    }
}
