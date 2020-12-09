<?php
/**
 * The control file of durationestimation of ZentaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     durationestimation
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class durationestimation extends control
{
    /**
     * Project duration estimation.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function index($projectID)
    {
        $workestimation = $this->loadModel('workestimation')->getBudget($projectID);
        if(empty($workestimation))
        {
            echo js::alert($this->lang->durationestimation->setWorkestimation);
            die(js::locate($this->createLink('workestimation', 'index', "projectID=$projectID")));
        }

        $this->app->loadLang('programplan');
        $project = $this->loadModel('program')->getPRJByID($projectID);
        $stages  = $this->loadModel('stage')->getStages('id_asc');

        $estimationList = $this->durationestimation->getListByProject($projectID);
        if(empty($estimationList)) $this->locate(inlink('create', "projectID=$projectID"));

        $this->view->title          = $this->lang->durationestimation->common . $this->lang->colon . $project->name;
        $this->view->position[]     = $this->lang->durationestimation->common;
        $this->view->workestimation = $workestimation;
        $this->view->estimationList = $estimationList;
        $this->view->project        = $project;
        $this->view->stages         = $stages;
        $this->display();
    }

    /**
     * Save the project duration estimate.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create($projectID = 0)
    {
        $workestimation = $this->loadModel('workestimation')->getBudget($projectID);
        if(empty($workestimation))
        {
            echo js::alert($this->lang->durationestimation->setWorkestimation);
            die(js::locate($this->createLink('workestimation', 'index', "projectID=$projectID")));
        }

        $this->app->loadLang('programplan');
        if(!empty($_POST))
        {
            $total = 0;
            foreach($this->post->workload as $value) $total += $value;

            $workloadTotal = $this->config->durationestimation->workloadTotal;
            if($total != $workloadTotal) $this->send(array('result' => 'fail', 'message' => $this->lang->durationestimation->workloadError));

            $this->durationestimation->save($projectID);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('index', "projectID={$projectID}")));
        }

        $project = $this->loadModel('program')->getPRJByID($projectID);

        $this->view->title          = $this->lang->durationestimation->common . $this->lang->colon . $project->name;
        $this->view->position[]     = $this->lang->durationestimation->common;
        $this->view->project        = $project;
        $this->view->stages         = $this->loadModel('stage')->getStages('id_asc');
        $this->view->estimationList = $this->durationestimation->getListByProject($projectID);
        $this->view->workestimation = $workestimation;

        $this->display();
    }

    /**
     * Calculate the end time of the duration.
     *
     * @param  int     $projectID
     * @param  int     $stage
     * @param  int     $workload
     * @param  int     $worktimeRate
     * @param  int     $people
     * @param  string  $startDate
     * @access public
     * @return void
     */
    public function ajaxGetDuration($projectID, $stage, $workload, $worktimeRate, $people, $startDate)
    {
        $startDate = str_replace('_', '-', $startDate);
        if($startDate == '0000-00-00') $this->send(array('result' => 'success', 'endDate' => '0000-00-00'));

        $estimation = $this->loadModel('workestimation')->getBudget($projectID);
        $duration   = $estimation->duration * $workload / 100;
        $divisor    = ($people == 0 || $estimation->dayHour == 0) ? 0 : $worktimeRate / 100 * $people * $estimation->dayHour;
        $duration   = $divisor == 0 ? 0 : $duration / $divisor;
        if(!$divisor) $this->send(array('result' => 'fail'));

        $holidays = array();
        $workDays = array();
        $i = 0;

        $this->loadModel('project');

        $startedTime = strtotime($startDate);
        $days = 0;
        for($i = 0; $days < $duration; $i ++)
        {
            $day = date('N', strtotime("+ $i days", $startedTime));
            if($this->config->project->weekend == 2)
            {
                if($day > 5) continue;
            }
            if($this->config->project->weekend == 1)
            {
                if($day > 6) continue;
            }
            $lastDay = date('Y-m-d', strtotime("+ $i days", $startedTime));
            $days ++;
        }

        $this->send(array('result' => 'success', 'endDate' => $lastDay));
    }
}
