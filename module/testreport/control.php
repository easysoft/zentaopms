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
        if(empty($reports) and common::hasPriv('testreport', 'create'))
        {
            $param = '';
            if($objectType == 'product' and $extra) $param = "objectID=$extra&objectType=testtask"; 
            if($objectType == 'project') $param = "objectID=$objectID&objectType=project"; 
            if($param) $this->locate($this->createLink('testreport', 'create', $param));
        }

        $projects = array();
        $tasks    = array();
        foreach($reports as $report)
        {
            $projects[$report->project] = $report->project;
            foreach(explode(',', $report->tasks) as $taskID) $tasks[$taskID] = $taskID;
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
            $this->loadModel('action')->create('testreport', $reportID, 'Opened');
            die(js::locate(inlink('view', "reportID=$reportID"), 'parent'));
        }

        if($objectType == 'testtask')
        {
            $task      = $this->testtask->getById($objectID);
            $productID = $this->commonAction($task->product, 'product');
            if($productID != $task->product) die(js::error($this->lang->error->accessDenied) . js::locate('back'));
            $productIdList[$productID] = $productID;

            $begin   = $task->begin;
            $end     = $task->end;
            $project = $this->project->getById($task->project);
            $builds  = array();
            if($task->build == 'trunk')
            {
                $stories = $this->story->getProjectStories($project->id);
                $bugs    = $this->testreport->getBugs4Test('trunk', $productID, $project->begin, date('Y-m-d', strtotime($task->begin) - 24 * 3600));
                foreach($stories as $id => $story)
                {
                    if($story->product != $task->product) unset($stories[$id]);
                }
            }
            else
            {
                $build   = $this->build->getById($task->build);
                $stories = empty($build->stories) ? array() : $this->story->getByList($build->stories);

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
            if($projectID != $objectID) die(js::error($this->lang->error->accessDenied) . js::locate('back'));

            $project = $this->project->getById($projectID);
            $tasks   = $this->testtask->getProjectTasks($projectID);
            $owners  = array();
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
     * Edit report 
     * 
     * @param  int    $reportID 
     * @param  string $from 
     * @access public
     * @return void
     */
    public function edit($reportID, $from = 'product')
    {
        if($_POST)
        {
            $changes = $this->testreport->update($reportID);
            if(dao::isError()) die(js::error(dao::getError()));

            $files      = $this->loadModel('file')->saveUpload('testreport', $reportID);
            $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
            $actionID   = $this->loadModel('action')->create('testreport', $reportID, 'Edited', $fileAction);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);

            die(js::locate(inlink('view', "reportID=$reportID&from=$from"), 'parent'));
        }

        $report  = $this->testreport->getById($reportID);
        $project = $this->project->getById($report->project);
        if($from == 'product' and is_numeric($report->product))
        {
            $product   = $this->product->getById($report->product);
            $productID = $this->commonAction($report->product, 'product');
            if($productID != $report->product) die(js::error($this->lang->error->accessDenied) . js::locate('back'));

            $browseLink = inlink('browse', "objectID=$productID&objectType=product");
            $this->view->position[] = html::a($browseLink, $product->name);
            $this->view->position[] = $this->lang->testreport->edit;
        }
        else
        {
            $projectID = $this->commonAction($report->project, 'project');
            if($projectID != $report->objectID) die(js::error($this->lang->error->accessDenied) . js::locate('back'));

            $browseLink = inlink('browse', "objectID=$projectID&objectType=project");
            $this->view->position[] = html::a($browseLink, $project->name);
            $this->view->position[] = $this->lang->testreport->edit;
        }

        if($report->objectType == 'testtask')
        {
            $productIdList[$report->product] = $report->product;

            $task    = $this->testtask->getById($report->objectID);
            $project = $this->project->getById($task->project);
            $builds  = array();
            if($task->build == 'trunk')
            {
                $stories = $this->story->getProjectStories($project->id);
                $bugs    = $this->testreport->getBugs4Test('trunk', $report->product, $project->begin, date('Y-m-d', strtotime($task->begin) - 24 * 3600));
                foreach($stories as $id => $story)
                {
                    if($story->product != $task->product) unset($stories[$id]);
                }
            }
            else
            {
                $build   = $this->build->getById($task->build);
                $stories = empty($build->stories) ? array() : $this->story->getByList($build->stories);

                $builds[$build->id] = $build;
                $bugs = $this->testreport->getBugs4Test($builds, $report->product, $report->begin, $report->end);
            }
            $tasks = array($task->id => $task);
        }
        else
        {
            $tasks = $this->testtask->getProjectTasks($report->project);
            $productIdList = array();
            foreach($tasks as $task) $productIdList[$task->product] = $task->product;

            $stories = $this->story->getProjectStories($project->id);
            $builds  = $this->build->getProjectBuilds($project->id);
            $bugs    = $this->testreport->getBugs4Test($builds, $productIdList, $report->begin, $report->end, 'project');
        }

        $cases   = $this->testreport->getTaskCases($tasks);
        $bugInfo = $this->testreport->getBugInfo($tasks, $productIdList, $report->begin, $report->end, $builds);

        $this->view->title = $report->title . $this->lang->testreport->edit;

        $this->view->report        = $report;
        $this->view->stories       = $stories;
        $this->view->bugs          = $bugs;
        $this->view->project       = $project;
        $this->view->productIdList = join(',', array_keys($productIdList));
        $this->view->tasks         = join(',', array_keys($tasks));
        $this->view->storySummary  = $this->product->summary($stories);

        $this->view->builds  = $builds;
        $this->view->users   = $this->user->getPairs('noletter|nodeleted|noclosed');

        $this->view->cases       = $cases;
        $this->view->caseSummary = $this->testreport->getResultSummary($tasks, $cases);

        $this->view->legacyBugs = $bugInfo['legacyBugs'];
        unset($bugInfo['legacyBugs']);
        $this->view->bugInfo = $bugInfo;

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
            if($productID != $report->product) die(js::error($this->lang->error->accessDenied) . js::locate('back'));

            $browseLink = inlink('browse', "objectID=$productID&objectType=product");
            $this->view->position[] = html::a($browseLink, $product->name);
        }
        else
        {
            $projectID = $this->commonAction($report->project, 'project');
            if($projectID != $report->objectID) die(js::error($this->lang->error->accessDenied) . js::locate('back'));

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
        $this->view->actions = $this->loadModel('action')->getList('testreport', $reportID);

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
