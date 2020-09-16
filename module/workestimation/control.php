<?php
/**
 * The control file of workestimation of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     workestimation
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
class workestimation extends control
{
    /**
     * Index.
     *
     * @param  int    $program
     * @access public
     * @return void
     */
    public function index($program = 0)
    {
        $program = $program ? $program : $this->session->PRJ;
        if($_POST)
        {
            $result = $this->workestimation->save($program);
            if($result) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
            $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $this->view->title        = $this->lang->workestimation->common;
        $this->view->programScale = $this->workestimation->getProgramScale($program);
        $this->view->hourPoint    = $this->config->custom->hourPoint;

        $budget = $this->workestimation->getBudget($program);
        if(!isset($this->config->project)) $this->config->project = new stdclass();
        if(empty($budget))
        {
            $this->app->loadConfig('estimate');
            $budget = new stdclass();
            $budget->scale         = $this->view->programScale;
            $budget->productivity  = zget($this->config->custom, 'efficiency', '');
            $budget->unitLaborCost = zget($this->config->custom, 'cost', '');
            $budget->dayHour       = zget($this->config->project, 'defaultWorkhours', '');
        }

        $this->view->budget      = $budget;
        $this->display();
    }
}

