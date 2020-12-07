<?php
/**
 * The control file of workestimation of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     workestimation
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class workestimation extends control
{
    /**
     * Project workload estimations.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function index($projectID = 0)
    {
        $projectID = $projectID ? $projectID : $this->session->PRJ;
        if($_POST)
        {
            $result = $this->workestimation->save($projectID);
            if($result) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
            $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $scale  = $this->workestimation->getProgramScale($projectID);
        $budget = $this->workestimation->getBudget($projectID);
        if(!isset($this->config->project)) $this->config->project = new stdclass();
        if(empty($budget))
        {
            $this->app->loadConfig('estimate');
            $budget = new stdclass();
            $budget->scale         = $scale;
            $budget->productivity  = zget($this->config->custom, 'efficiency', '');
            $budget->unitLaborCost = zget($this->config->custom, 'cost', '');
            $budget->dayHour       = zget($this->config->project, 'defaultWorkhours', '');
        }

        $this->view->title        = $this->lang->workestimation->common;
        $this->view->position[]   = $this->lang->workestimation->common;
        $this->view->hourPoint    = $this->config->custom->hourPoint;
        $this->view->programScale = $scale;
        $this->view->budget       = $budget;
        $this->display();
    }
}

