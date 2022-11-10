<?php
/**
 * The testresults entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class testresultsEntry extends entry
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
        $version = $this->param('version', 0);
        $runID   = $this->param('runID', 0);

        $control = $this->loadController('testtask', 'results');
        $control->results($runID, $caseID, $version);

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

        $case    = $this->loadModel('testcase')->getByID($caseID);
        $runID   = $this->param('runID', 0);
        $version = $this->param('version', $case->version);
        $steps   = $this->getStepIDList($runID, $caseID, $version);

        $this->setPost('case',  $caseID);
        $this->setPost('version', $version);

        /* Set steps and expects. */
        if(isset($this->requestBody->steps))
        {
            $results = array();
            $reals   = array();
            foreach($this->requestBody->steps as $index => $step)
            {
                /* When post data more than case steps, break after take useful data. */
                if($index + 1 > count($steps)) break;

                $stepID           = $steps[$index];
                $results[$stepID] = $step->result;
                $reals[$stepID]   = $step->real;
            }
            $this->setPost('steps',  $results);
            $this->setPost('reals',  $reals);
        }

        $control = $this->loadController('testtask', 'runCase');
        $control->runCase($runID, $caseID, $version);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        return $this->send(200, array());
    }

    /**
     * Get steps key.
     *
     * @param  int    $runID
     * @access public
     * @return array
     */
    public function getStepIDList($runID, $caseID, $version)
    {
        if($runID)
        {
            $run = $this->testtask->getRunById($runID);
        }
        else
        {
            $run = new stdclass();
            $run->case = $this->loadModel('testcase')->getById($caseID, $version);
        }

        foreach($run->case->steps as $key => $step)
        {
            /* Unset step if step is a group. */
            if($step->type == 'group')
            {
                unset($run->case->steps[$key]);
            }
        }

        return array_keys($run->case->steps);
    }
}
