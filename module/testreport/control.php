<?php
/**
 * The control file of testreport of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testreport
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class testreport extends control
{
    /**
     * Construct 
     * 
     * @param  string $moduleName 
     * @param  string $methodName 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('project');
        $this->loadModel('product');
        $this->loadModel('story');
        $this->loadModel('build');
        $this->loadModel('bug');
        $this->loadModel('tree');
        $this->loadModel('testcase');
        $this->loadModel('testtask');
        $this->loadModel('user');
        $this->app->loadLang('report');
    }

    /**
     * Browse report. 
     * 
     * @param  int    $objectID 
     * @param  string $objectType 
     * @param  string $extra 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($objectID = 0, $objectType = 'product', $extra = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($objectType != 'product' and $objectType != 'project') die('Type Error!');
        $this->session->set('reportList', $this->app->getURI(true));

        $objectID = $this->commonAction($objectID, $objectType);
        $object   = $this->$objectType->getById($objectID);
        if($extra) $task = $this->testtask->getById($extra);
        $name = $extra ? $task->name : $object->name;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $reports = $this->testreport->getList($objectID, $objectType, $extra, $orderBy, $pager);

        $projects = array();
        $tasks    = array();
        foreach($reports as $report)
        {
            if($report->objectType == 'project')  $projects[$report->objectID] = $report->objectID;
            if($report->objectType == 'testtask') $tasks[$report->objectID]    = $report->objectID;
        }
        if($projects) $projects = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($projects)->fetchPairs('id', 'name');
        if($tasks)    $tasks    = $this->dao->select('id,name')->from(TABLE_TESTTASK)->where('id')->in($tasks)->fetchPairs('id', 'name');

        $this->view->title      = $name . $this->lang->colon . $this->lang->testreport->common;
        $this->view->position[] = html::a(inlink('browse', "objectID=$objectID&objectType=$objectType&extra=$extra"), $extra ? $task->name : $object->name);
        $this->view->position[] = $this->lang->testreport->browse;

        $this->view->reports    = $reports;
        $this->view->orderBy    = $orderBy;
        $this->view->objectID   = $objectID;
        $this->view->objectType = $objectType;
        $this->view->extra      = $extra;
        $this->view->pager      = $pager;
        $this->view->users      = $this->user->getPairs('noletter|nodeleted|noclosed');
        $this->view->tasks      = $tasks;
        $this->view->projects   = $projects;
        $this->display();
    }

    /**
     * Create report. 
     * 
     * @param  int    $objectID 
     * @param  string $objectType 
     * @access public
     * @return void
     */
    public function create($objectID, $objectType = 'testtask')
    {
        if($_POST)
        {
            $reportID = $this->testreport->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate(inlink('view', "reportID=$reportID"), 'parent'));
        }

        if($objectType == 'testtask')
        {
            $task      = $this->testtask->getById($objectID);
            $productID = $this->commonAction($task->product, 'product');
            if($productID != $task->product) die('deny access');
            $productIdList[$productID] = $productID;

            $begin   = $task->begin;
            $end     = $task->end;
            $project = $this->project->getById($task->project);
            $builds  = array();
            if($task->build == 'trunk')
            {
                $stories = $this->story->getProjectStories($project->id);
                $bugs    = $this->testreport->getBugs4Test('trunk', $productID, $begin, $end);
            }
            else
            {
                $build = $this->build->getById($task->build);
                $stories = $this->story->getByList($build->stories);

                $builds[$build->id] = $build;
                $bugs = $this->testreport->getBugs4Test($builds, $productID, $begin, $end);
            }

            $tasks = array($task->id => $task);
            $owner = $task->owner;

            $this->view->title       = $task->name . $this->lang->testreport->create;
            $this->view->position[]  = html::a(inlink('browse', "objectID=$productID&objectType=product&extra={$task->id}"), $task->name);
            $this->view->position[]  = $this->lang->testreport->create;
            $this->view->reportTitle = date('Y-m-d') . " TESTTASK#{$task->id} {$task->name} {$this->lang->testreport->common}";
        }
        elseif($objectType == 'project')
        {
            $projectID = $this->commonAction($objectID, 'project');
            if($projectID != $objectID) die('deny access');

            $project = $this->project->getById($projectID);
            $tasks   = $this->testtask->getProjectTasks($projectID);
            $productIdList = array();
            foreach($tasks as $task)
            {
                $owners[$task->owner] = $task->owner;
                $productIdList[$task->product] = $task->product;
            }
            $stories = $this->story->getProjectStories($project->id);
            $builds  = $this->build->getProjectBuilds($project->id);

            $begin = $project->begin;
            $end   = $project->end;
            $owner = current($owners);
            $bugs  = $this->testreport->getBugs4Test($builds, $productIdList, $begin, $end, 'project');

            $this->view->title       = $project->name . $this->lang->testreport->create;
            $this->view->position[]  = html::a(inlink('browse', "objectID=$projectID&objectType=project"), $project->name);
            $this->view->position[]  = $this->lang->testreport->create;
            $this->view->reportTitle = date('Y-m-d') . " PROJECT#{$project->id} {$project->name} {$this->lang->testreport->common}";

        }
        $cases   = $this->testreport->getTaskCases($tasks);
        $bugInfo = $this->testreport->getBugInfo($tasks, $productIdList, $begin, $end, $builds);
        $modules = array();
        foreach($productIdList as $productID) $modules += $this->tree->getOptionMenu($productID, $viewType = 'bug');


        $this->view->begin   = $begin;
        $this->view->end     = $end;
        $this->view->members = $this->dao->select('lastRunner')->from(TABLE_TESTRESULT)->where('run')->in(array_keys($tasks))->fetchPairs('lastRunner', 'lastRunner');
        $this->view->owner   = $owner;

        $this->view->stories       = $stories;
        $this->view->bugs          = $bugs;
        $this->view->project       = $project;
        $this->view->productIdList = join(',', array_keys($productIdList));
        $this->view->tasks         = join(',', array_keys($tasks));
        $this->view->storySummary  = $this->product->summary($stories);

        $this->view->builds  = $builds;
        $this->view->users   = $this->user->getPairs('noletter|nodeleted|noclosed');
        $this->view->modules = $modules;

        $this->view->cases       = $cases;
        $this->view->caseSummary = $this->testreport->getResultSummary($tasks, $cases);

        $this->view->legacyBugs = $bugInfo['legacyBugs'];
        unset($bugInfo['legacyBugs']);
        $this->view->bugInfo = $bugInfo;

        $this->view->objectID   = $objectID;
        $this->view->objectType = $objectType;
        $this->display();
    }

    /**
     * View report. 
     * 
     * @param  int    $reportID 
     * @access public
     * @return void
     */
    public function view($reportID, $from = 'product')
    {
        $report  = $this->testreport->getById($reportID);
        $project = $this->project->getById($report->project);
        if($from == 'product' and is_numeric($report->product))
        {
            $product   = $this->product->getById($report->product);
            $productID = $this->commonAction($report->product, 'product');
            if($productID != $report->product) die('deny access');

            $browseLink = inlink('browse', "objectID=$productID&objectType=product");
            $this->view->position[] = html::a($browseLink, $product->name);
        }
        else
        {
            $projectID = $this->commonAction($report->project, 'project');
            if($projectID != $report->objectID) die('deny access');

            $browseLink = inlink('browse', "objectID=$projectID&objectType=project");
            $this->view->position[] = html::a($browseLink, $project->name);

        }

        $stories = $report->stories ? $this->story->getByList($report->stories) : array();
        $results = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('run')->in($report->tasks)->andWhere('`case`')->in($report->cases)->fetchAll();
        $failResults = array();
        $runCasesNum = array();
        foreach($results as $result)
        {
            $runCasesNum[$result->case] = $result->case;
            if($result->caseResult == 'fail') $failResults[$result->case] = $result->case;
        }

        $tasks   = $report->tasks ? $this->testtask->getByList($report->tasks) : array();;
        $builds  = $report->builds ? $this->build->getByList($report->builds) : array();
        $cases   = $this->testreport->getTaskCases($tasks, $report->cases);
        $bugInfo = $this->testreport->getBugInfo($tasks, $report->product, $report->begin, $report->end, $builds);
        $modules = array();
        foreach(explode(',', $report->product) as $productID) $modules += $this->tree->getOptionMenu($productID, $viewType = 'bug');

        $this->view->title      = $report->title;
        $this->view->browseLink = $browseLink;
        $this->view->position[] = $report->title;

        $this->view->report  = $report;
        $this->view->project = $project;
        $this->view->stories = $stories;
        $this->view->bugs    = $report->bugs ? $this->bug->getByList($report->bugs) : array();
        $this->view->builds  = $builds;
        $this->view->cases   = $cases;
        $this->view->users   = $this->user->getPairs('noletter|nodeleted|noclosed');
        $this->view->modules = $modules;

        $this->view->storySummary = $this->product->summary($stories);
        $this->view->caseSummary  = $this->testreport->getResultSummary($tasks, $cases);

        $this->view->legacyBugs = $bugInfo['legacyBugs'];
        unset($bugInfo['legacyBugs']);
        $this->view->bugInfo    = $bugInfo;
        $this->display();
    }

    /**
     * Delete report. 
     * 
     * @param  int    $reportID 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function delete($reportID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->testreport->confirmDelete, inlink('delete', "reportID=$reportID&confirm=yes")));
        }
        else
        {
            $this->testreport->delete(TABLE_TESTREPORT, $reportID);
            die(js::locate($this->session->reportList, 'parent'));
        }
    }

    /**
     * Export report. 
     * 
     * @param  int    $reportID 
     * @access public
     * @return void
     */
    public function export($reportID)
    {
        if($_POST)
        {
            $report = $this->testreport->getById($reportID);
            $data   = fixer::input('post')->get();

            $this->session->set('notHead', true);
            $output = $this->fetch('testreport', 'view', array('reportID' =>$reportID));
            $this->session->set('notHead', false);
            $css = '<style>' . $this->getCSS('testreport', 'export') . '</style>';
            $js  = '<script>' . $this->getJS('testreport', 'export') . '</script>';
            $exportNotice  = "<p style='text-align:right;color:grey'>" . $this->lang->testreport->exportNotice . '</p>';
            $content = "<!DOCTYPE html>\n<html lang='zh-cn'>\n<head>\n<meta charset='utf-8'>\n<title>{$report->title}</title>\n$css\n$js\n</head>\n<body onload='tab()'>\n<h1>{$report->title}</h1>\n$output\n$exportNotice</body></html>";
            $this->fetch('file',  'sendDownHeader', array('fileName' => $data->fileName, 'fileType' => $data->fileType, 'content' =>$content));
        }
        $this->view->customExport = false;
        $this->display();
    }

    /**
     * Common action.
     * 
     * @param  int    $objectID 
     * @param  string $objectType 
     * @access public
     * @return int
     */
    public function commonAction($objectID, $objectType = 'product')
    {
        if($objectType == 'product')
        {
            $this->products = $this->product->getPairs('nocode');
            $productID      = $this->product->saveState($objectID, $this->products);
            $this->testreport->setMenu($this->products, $productID);
            return $productID; 
        }
        elseif($objectType == 'project')
        {
            $this->projects = $this->project->getPairs('nocode');
            $projectID      = $this->project->saveState($objectID, $this->projects);
            $this->project->setMenu($this->projects, $projectID);
            $this->lang->testreport->menu = $this->lang->project->menu;
            $this->lang->testreport->menu->testtask['subModule'] = 'testreport';
            $this->lang->menugroup->testreport = 'project';
            return $projectID; 
        }
    }
}
