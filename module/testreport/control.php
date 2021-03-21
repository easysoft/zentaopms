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
    public $projectID = 0;

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
        $this->loadModel('execution');
        $this->loadModel('product');
        $this->loadModel('story');
        $this->loadModel('build');
        $this->loadModel('bug');
        $this->loadModel('tree');
        $this->loadModel('testcase');
        $this->loadModel('testtask');
        $this->loadModel('user');
        $this->app->loadLang('report');

        /* Set testreport menu group. */
        $this->projectID = isset($_GET['PRJ']) ? $_GET['PRJ'] : 0;
        if(!$this->projectID)
        {
            $this->app->loadConfig('qa');
            foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
        }
        else 
        {    
            $this->lang->testreport->menu    = $this->lang->projectQa->menu;
            $this->lang->testreport->subMenu = $this->lang->projectQa->subMenu;
        }
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
        if($objectType != 'product' and $objectType != 'execution') die('Type Error!');
        $this->session->set('reportList', $this->app->getURI(true));

        $objectID = $this->commonAction($objectID, $objectType);
        $object   = $this->$objectType->getById($objectID);
        if($extra) $task = $this->testtask->getById($extra);

        $title = $extra ? $task->name : $object->name;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $reports = $this->testreport->getList($objectID, $objectType, $extra, $orderBy, $pager);

        if($objectType == 'execution' and isset($_POST['taskIdList']))
        {
            $taskIdList = $_POST['taskIdList'];
            foreach($reports as $reportID => $report)
            {
                $tasks = explode(',', $report->tasks);
                if(count($tasks) != count($taskIdList) or array_diff($tasks, $taskIdList)) unset($reports[$reportID]);
            }
            $pager->setRecTotal(count($reports));
        }

        if(empty($reports) and common::hasPriv('testreport', 'create'))
        {
            $param = '';
            if($objectType == 'product' and $extra) $param = "objectID=$extra&objectType=testtask";
            if($objectType == 'project')
            {
                $param = "objectID=$objectID&objectType=project";
                if(isset($_POST['taskIdList'])) $param .= '&extra=' . join(',', $_POST['taskIdList']);
            }
            if($param) $this->locate($this->createLink('testreport', 'create', $param));
        }

        $executions = array();
        $tasks      = array();
        foreach($reports as $report)
        {
            $executions[$report->execution] = $report->execution;
            foreach(explode(',', $report->tasks) as $taskID) $tasks[$taskID] = $taskID;
        }
        if($executions) $executions = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($executions)->fetchPairs('id', 'name');
        if($tasks)      $tasks      = $this->dao->select('id,name')->from(TABLE_TESTTASK)->where('id')->in($tasks)->fetchPairs('id', 'name');

        $this->view->title      = $title . $this->lang->colon . $this->lang->testreport->common;
        $this->view->position[] = html::a(inlink('browse', "objectID=$objectID&objectType=$objectType&extra=$extra"), $title);
        $this->view->position[] = $this->lang->testreport->browse;

        $this->view->reports      = $reports;
        $this->view->orderBy      = $orderBy;
        $this->view->objectID     = $objectID;
        $this->view->objectType   = $objectType;
        $this->view->extra        = $extra;
        $this->view->pager        = $pager;
        $this->view->users        = $this->user->getPairs('noletter|noclosed|nodeleted');
        $this->view->tasks        = $tasks;
        $this->view->executions   = $executions;
        $this->view->canBeChanged = common::canModify($objectType, $object); // Determines whether an object is editable.
        $this->display();
    }

    /**
     * Create report.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $extra
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return void
     */
    public function create($objectID = 0, $objectType = 'testtask', $extra = '', $begin = '', $end = '')
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
            if(empty($objectID) and $extra) $productID = $extra;
            if($objectID)
            {
                $task      = $this->testtask->getById($objectID);
                $productID = $this->commonAction($task->product, 'product');
            }

            $taskPairs         = array();
            $scopeAndStatus[0] = 'local';
            $scopeAndStatus[1] = 'totalStatus';
            $tasks = $this->testtask->getProductTasks($productID, 0, 'id_desc', null, $scopeAndStatus);
            foreach($tasks as $testTask)
            {
                if($testTask->build == 'trunk') continue;
                $taskPairs[$testTask->id] = $testTask->name;
            }
            if(empty($taskPairs)) die(js::alert($this->lang->testreport->noTestTask) . js::locate('back'));

            if(empty($objectID))
            {
                $objectID  = key($taskPairs);
                $task      = $this->testtask->getById($objectID);
                $productID = $this->commonAction($task->product, 'product');
            }
            $this->view->taskPairs = $taskPairs;
        }

        if(empty($objectID)) die(js::alert($this->lang->testreport->noObjectID) . js::locate('back'));
        if($objectType == 'testtask')
        {
            if($productID != $task->product) die(js::error($this->lang->error->accessDenied) . js::locate('back'));
            $productIdList[$productID] = $productID;

            $begin     = !empty($begin) ? date("Y-m-d", strtotime($begin)) : $task->begin;
            $end       = !empty($end) ? date("Y-m-d", strtotime($end)) : $task->end;
            $execution = $this->execution->getById($task->execution);
            $builds    = array();
            if($task->build == 'trunk')
            {
                echo js::alert($this->lang->testreport->errorTrunk);
                die(js::locate('back'));
            }
            else
            {
                $build   = $this->build->getById($task->build);
                $stories = empty($build->stories) ? array() : $this->story->getByList($build->stories);

                if(!empty($build->id)) $builds[$build->id] = $build;
                $bugs = $this->testreport->getBugs4Test($builds, $productID, $begin, $end);
            }

            $tasks = array($task->id => $task);
            $owner = $task->owner;

            $this->setChartDatas($objectID);

            $this->view->title       = $task->name . $this->lang->testreport->create;
            $this->view->position[]  = html::a(inlink('browse', "objectID=$productID&objectType=product&extra={$task->id}"), $task->name);
            $this->view->position[]  = $this->lang->testreport->create;
            $this->view->reportTitle = date('Y-m-d') . " TESTTASK#{$task->id} {$task->name} {$this->lang->testreport->common}";
        }
        elseif($objectType == 'project')
        {
            $executionID = $this->commonAction($objectID, 'execution');
            if($executionID != $objectID) die(js::error($this->lang->error->accessDenied) . js::locate('back'));

            $execution = $this->execution->getById($executionID);
            $tasks     = $this->testtask->getExecutionTasks($executionID);
            $owners    = array();
            $buildIdList   = array();
            $productIdList = array();
            foreach($tasks as $i => $task)
            {
                if(!empty($extra) and strpos(",{$extra},", ",{$task->id},") === false)
                {
                    unset($tasks[$i]);
                    continue;
                }

                $owners[$task->owner] = $task->owner;
                $productIdList[$task->product] = $task->product;
                $this->setChartDatas($task->id);
                if($task->build != 'trunk') $buildIdList[$task->build] = $task->build;
            }
            if(count($productIdList) > 1)
            {
                echo(js::alert($this->lang->testreport->moreProduct));
                die(js::locate('back'));
            }

            $builds  = $this->build->getByList($buildIdList);
            $stories = !empty($builds) ? $this->testreport->getStories4Test($builds) : $this->story->getExecutionStories($execution->id);;

            $begin = !empty($begin) ? date("Y-m-d", strtotime($begin)) : $execution->begin;
            $end   = !empty($end) ? date("Y-m-d", strtotime($end)) : $execution->end;
            $owner = current($owners);
            $bugs  = $this->testreport->getBugs4Test($builds, $productIdList, $begin, $end, 'execution');

            $this->view->title       = $execution->name . $this->lang->testreport->create;
            $this->view->position[]  = html::a(inlink('browse', "objectID=$executionID&objectType=execution"), $execution->name);
            $this->view->position[]  = $this->lang->testreport->create;
            $this->view->reportTitle = date('Y-m-d') . " PROJECT#{$execution->id} {$execution->name} {$this->lang->testreport->common}";

        }
        $cases   = $this->testreport->getTaskCases($tasks, $begin, $end);
        $bugInfo = $this->testreport->getBugInfo($tasks, $productIdList, $begin, $end, $builds);
        $this->view->begin   = $begin;
        $this->view->end     = $end;
        $this->view->members = $this->dao->select('DISTINCT lastRunner')->from(TABLE_TESTRUN)->where('task')->in(array_keys($tasks))->fetchPairs('lastRunner', 'lastRunner');
        $this->view->owner   = $owner;

        $this->view->stories       = $stories;
        $this->view->bugs          = $bugs;
        $this->view->execution     = $execution;
        $this->view->productIdList = join(',', array_keys($productIdList));
        $this->view->tasks         = join(',', array_keys($tasks));
        $this->view->storySummary  = $this->product->summary($stories);

        $this->view->builds  = $builds;
        $this->view->users   = $this->user->getPairs('noletter|noclosed|nodeleted');

        $this->view->cases       = $cases;
        $this->view->caseSummary = $this->testreport->getResultSummary($tasks, $cases, $begin, $end);

        $perCaseResult = $this->testreport->getPerCaseResult4Report($tasks, $cases, $begin, $end);
        $perCaseRunner = $this->testreport->getPerCaseRunner4Report($tasks, $cases, $begin, $end);
        $this->view->datas['testTaskPerRunResult'] = $this->loadModel('report')->computePercent($perCaseResult);
        $this->view->datas['testTaskPerRunner']    = $this->report->computePercent($perCaseRunner);

        $this->view->legacyBugs = $bugInfo['legacyBugs'];
        unset($bugInfo['legacyBugs']);
        $this->view->bugInfo = $bugInfo;

        $this->view->objectID   = $objectID;
        $this->view->objectType = $objectType;
        $this->view->extra      = $extra;
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
    public function edit($reportID, $from = 'product', $begin = '', $end ='')
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

        $report    = $this->testreport->getById($reportID);
        $execution = $this->execution->getById($report->execution);
        $begin     = !empty($begin) ? date("Y-m-d", strtotime($begin)) : $report->begin;
        $end       = !empty($end) ? date("Y-m-d", strtotime($end)) : $report->end;
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
            $executionID = $this->commonAction($report->execution, 'execution');
            if($executionID != $report->objectID) die(js::error($this->lang->error->accessDenied) . js::locate('back'));

            $browseLink = inlink('browse', "objectID=$executionID&objectType=project");
            $this->view->position[] = html::a($browseLink, $execution->name);
            $this->view->position[] = $this->lang->testreport->edit;
        }

        if($report->objectType == 'testtask')
        {
            $productIdList[$report->product] = $report->product;

            $task      = $this->testtask->getById($report->objectID);
            $execution = $this->execution->getById($task->execution);
            $builds    = array();
            if($task->build == 'trunk')
            {
                echo js::alert($this->lang->testreport->errorTrunk);
                die(js::locate('back'));
            }
            else
            {
                $build   = $this->build->getById($task->build);
                $stories = empty($build->stories) ? array() : $this->story->getByList($build->stories);

                if(!empty($build->id)) $builds[$build->id] = $build;
                $bugs = $this->testreport->getBugs4Test($builds, $report->product, $begin, $end);
            }
            $tasks = array($task->id => $task);

            $this->setChartDatas($report->objectID);
        }
        else
        {
            $tasks = $this->testtask->getByList($report->tasks);
            $productIdList[$report->product] = $report->product;

            foreach($tasks as $task) $this->setChartDatas($task->id);

            $builds  = $this->build->getByList($report->builds);
            $stories = !empty($builds) ? $this->testreport->getStories4Test($builds) : $this->story->getExecutionStories($execution->id);;
            $bugs    = $this->testreport->getBugs4Test($builds, $productIdList, $begin, $end, 'execution');
        }

        $cases   = $this->testreport->getTaskCases($tasks, $begin, $end);
        $bugInfo = $this->testreport->getBugInfo($tasks, $productIdList, $begin, $end, $builds);

        $this->view->title = $report->title . $this->lang->testreport->edit;

        $this->view->report        = $report;
        $this->view->from          = $from;
        $this->view->begin         = $begin;
        $this->view->end           = $end;
        $this->view->stories       = $stories;
        $this->view->bugs          = $bugs;
        $this->view->execution     = $execution;
        $this->view->productIdList = join(',', array_keys($productIdList));
        $this->view->tasks         = join(',', array_keys($tasks));
        $this->view->storySummary  = $this->product->summary($stories);

        $this->view->builds  = $builds;
        $this->view->users   = $this->user->getPairs('noletter|noclosed|nodeleted');

        $this->view->cases       = $cases;
        $this->view->caseSummary = $this->testreport->getResultSummary($tasks, $cases, $begin, $end);

        $perCaseResult = $this->testreport->getPerCaseResult4Report($tasks, $cases, $begin, $end);
        $perCaseRunner = $this->testreport->getPerCaseRunner4Report($tasks, $cases, $begin, $end);
        $this->view->datas['testTaskPerRunResult'] = $this->loadModel('report')->computePercent($perCaseResult);
        $this->view->datas['testTaskPerRunner']    = $this->report->computePercent($perCaseRunner);

        $this->view->legacyBugs = $bugInfo['legacyBugs'];
        unset($bugInfo['legacyBugs']);
        $this->view->bugInfo = $bugInfo;

        $this->display();
    }

    /**
     * View report.
     *
     * @param  int    $reportID
     * @param  string $from
     * @param  string $tab
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view($reportID, $from = 'product', $tab = 'basic', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $report  = $this->testreport->getById($reportID);
        if(!$report) die(js::error($this->lang->notFound) . js::locate('back'));
        $this->session->PRJ = $report->project;

        $execution = $this->execution->getById($report->execution);
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
            $executionID = $this->commonAction($report->execution, 'execution');
            if($executionID != $report->objectID) die(js::error($this->lang->error->accessDenied) . js::locate('back'));

            $browseLink = inlink('browse', "objectID=$executionID&objectType=project");
            $this->view->position[] = html::a($browseLink, $execution->name);
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

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $tasks      = $report->tasks ? $this->testtask->getByList($report->tasks) : array();;
        $builds     = $report->builds ? $this->build->getByList($report->builds) : array();
        $cases      = $this->testreport->getTaskCases($tasks, $report->begin, $report->end, $report->cases, $pager);
        $caseIdList = $this->testreport->getCaseIdList($reportID);
        $bugInfo    = $this->testreport->getBugInfo($tasks, $report->product, $report->begin, $report->end, $builds);

        if($report->objectType == 'testtask')
        {
            $this->setChartDatas($report->objectID);
        }
        elseif($tasks)
        {
            foreach($tasks as $task) $this->setChartDatas($task->id);
        }

        $this->view->title      = $report->title;
        $this->view->browseLink = $browseLink;
        $this->view->position[] = $report->title;

        $this->view->tab       = $tab;
        $this->view->pager     = $pager;
        $this->view->report    = $report;
        $this->view->execution = $execution;
        $this->view->stories   = $stories;
        $this->view->bugs      = $report->bugs ? $this->bug->getByList($report->bugs) : array();
        $this->view->builds    = $builds;
        $this->view->cases     = $cases;
        $this->view->users     = $this->user->getPairs('noletter|noclosed|nodeleted');
        $this->view->actions   = $this->loadModel('action')->getList('testreport', $reportID);

        $this->view->storySummary = $this->product->summary($stories);
        $this->view->caseSummary  = $this->testreport->getResultSummary($tasks, $caseIdList, $report->begin, $report->end);

        $perCaseResult = $this->testreport->getPerCaseResult4Report($tasks, $caseIdList, $report->begin, $report->end);
        $perCaseRunner = $this->testreport->getPerCaseRunner4Report($tasks, $caseIdList, $report->begin, $report->end);
        $this->view->datas['testTaskPerRunResult'] = $this->loadModel('report')->computePercent($perCaseResult);
        $this->view->datas['testTaskPerRunner']    = $this->report->computePercent($perCaseRunner);

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
            $projectID      = $this->lang->navGroup->testreport == 'qa' ? 0 : $this->session->PRJ;
            $this->products = $this->product->getProductPairsByProject($projectID);
            if(empty($this->products)) die($this->locate($this->createLink('product', 'showErrorNone', 'fromModule=testreport&moduleGroup=' . $this->lang->navGroup->bug . '&activeMenu=report')));
            $productID      = $this->product->saveState($objectID, $this->products);
            $this->loadModel('qa')->setMenu($this->products, $productID);
            return $productID;
        }
        elseif($objectType == 'execution')
        {
            $this->executions = $this->execution->getPairs($this->session->PRJ, 'all', 'nocode');
            $executionID      = $this->execution->saveState($objectID, $this->executions);
            $this->execution->setMenu($this->executions, $executionID);
            $this->lang->testreport->menu = $this->lang->execution->menu;
            return $executionID;
        }
    }

    /**
     * Set chart datas of cases.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function setChartDatas($taskID)
    {
        $this->loadModel('report');
        $task   = $this->loadModel('testtask')->getById($taskID);
        foreach($this->lang->testtask->report->charts as $chart => $title)
        {
            if(strpos($chart, 'testTask') === false) continue;

            $chartFunc   = 'getDataOf' . $chart;
            $chartData   = $this->testtask->$chartFunc($taskID);
            $chartOption = $this->testtask->mergeChartOption($chart);
            if(!empty($chartType)) $chartOption->type = $chartType;

            $this->view->charts[$chart] = $chartOption;
            if(isset($this->view->datas[$chart]))
            {
                $existDatas = $this->view->datas[$chart];
                $sum        = 0;
                foreach($chartData as $key => $data)
                {
                    if(isset($existDatas[$key]))
                    {
                        $data->value += $existDatas[$key]->value;
                        unset($existDatas[$key]);
                    }
                    $sum += $data->value;
                }
                foreach($existDatas as $key => $data)
                {
                    $sum += $data->value;
                    $chartData[$key] = $data;
                }
                if($sum)
                {
                    foreach($chartData as $data) $data->percent = round($data->value / $sum, 2);
                }
                ksort($chartData);
                $this->view->datas[$chart] = $chartData;
            }
            else
            {
                $this->view->datas[$chart] = $this->report->computePercent($chartData);
            }
        }
    }
}
