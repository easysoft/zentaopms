<?php
/**
 * The control file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class report extends control
{
    public function index()
    {
        $this->locate(inlink('projectdeviation')); 
    }
    
    public function projectDeviation()
    {
        $this->view->header->title = $this->lang->report->projectDeviation;
        $this->view->projects      = $this->report->getProjects();
        $this->display();
    }

    public function productInfo()
    {
        $this->app->loadLang('product');
        $this->app->loadLang('productplan');
        $this->app->loadLang('story');
        $this->view->header->title = $this->lang->report->productInfo;
        $this->view->products      = $this->report->getProducts();
        $this->display();
    }

    public function bugSummary($begin = 0, $end = 0)
    {
        $this->app->loadLang('bug');
        if($begin == 0) 
        {
            $begin = date('Y-m-d', strtotime('last month'));
        }
        else
        {
            $begin = date('Y-m-d', strtotime($begin));
        }
        if($end == 0)
        {
            $end = date('Y-m-d', strtotime('now'));
        }
        else
        {
            $end = date('Y-m-d', strtotime($end));
        }
        $this->view->header->title = $this->lang->report->bugSummary;
        $this->view->begin         = $begin;
        $this->view->end           = $end;
        $this->view->bugs          = $this->report->getBugs($begin, $end);
        $this->display(); 
    }

    public function workload($begin = 0, $end = 0)
    {
        if($begin == 0) 
        {
            $begin = date('Y-m-d', strtotime('last month'));
        }
        else
        {
            $begin = date('Y-m-d', strtotime($begin));
        }
        if($end == 0)
        {
            $end = date('Y-m-d', strtotime('now'));
        }
        else
        {
            $end = date('Y-m-d', strtotime($end));
        }
        $this->view->header->title = $this->lang->report->workload;
        $this->view->workload      = $this->report->getWorkload();
        $this->display();
    }
}
