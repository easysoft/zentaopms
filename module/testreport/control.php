<?php
/**
 * The control file of testreport of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testreport
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class testreport extends control
{
    public $projectID = 0;

    /**
     * All products.
     *
     * @var    array
     * @access public
     */
    public $products = array();

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

        /* Get product data. */
        $products = array();
        $objectID = 0;
        $tab      = ($this->app->tab == 'project' or $this->app->tab == 'execution') ? $this->app->tab : 'qa';
        if(!isonlybody())
        {
            if($this->app->tab == 'project')
            {
                $objectID = $this->session->project;
                $products  = $this->product->getProducts($objectID, 'all', '', false);
            }
            elseif($this->app->tab == 'execution')
            {
                $objectID = $this->session->execution;
                $products = $this->product->getProducts($objectID, 'all', '', false);
            }
            else
            {
                $products = $this->product->getPairs();
            }
            if(empty($products) and !helper::isAjaxRequest()) return print($this->locate($this->createLink('product', 'showErrorNone', "moduleName=$tab&activeMenu=testreport&objectID=$objectID")));
        }
        else
        {
            $products = $this->product->getPairs();
        }
        $this->view->products = $this->products = $products;
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
        if(strpos('product|execution|project', $objectType) === false) return print('Type Error!');

        $objectID = $this->commonAction($objectID, $objectType);
        $object   = $this->$objectType->getById($objectID);
        if($extra) $task = $this->testtask->getById($extra);

        $title = $extra ? $task->name : $object->name;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $reports = $this->testreport->getList($objectID, $objectType, $extra, $orderBy, $pager);

        if(strpos('project|execution', $objectType) !== false and ($extra or isset($_POST['taskIdList'])))
        {
            $taskIdList = isset($_POST['taskIdList']) ? $_POST['taskIdList'] : array($extra);
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
            if(($objectType == 'project' or $objectType == 'execution') and ($extra or !empty($_POST['taskIdList'])))
            {
                $param  = "objectID=$objectID&objectType=$objectType";
                $param .= isset($_POST['taskIdList']) ? '&extra=' . join(',', $_POST['taskIdList']) : '&extra=' . $extra;
            }
            if($param) $this->locate($this->createLink('testreport', 'create', $param));
        }

        $this->session->set('reportList', $this->app->getURI(true), $this->app->tab);

        $executions = array();
        $tasks      = array();
        foreach($reports as $report)
        {
            $executions[$report->execution] = $report->execution;
            foreach(explode(',', $report->tasks) as $taskID) $tasks[$taskID] = $taskID;
        }
        if($executions) $executions = $this->dao->select('id,name,multiple')->from(TABLE_PROJECT)->where('id')->in($executions)->fetchAll('id');
        if($tasks)      $tasks      = $this->dao->select('id,name')->from(TABLE_TESTTASK)->where('id')->in($tasks)->fetchPairs('id', 'name');

        $this->view->title      = $title . $this->lang->colon . $this->lang->testreport->common;
        $this->view->position[] = html::a(inlink('browse', "objectID=$objectID&objectType=$objectType&extra=$extra"), $title);
        $this->view->position[] = $this->lang->testreport->browse;

        $this->view->reports      = $reports;
        $this->view->orderBy      = $orderBy;
        $this->view->objectID     = $objectID;
        $this->view->objectType   = $objectType;
        $this->view->object       = $object;
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
            if(dao::isError()) return print(js::error(dao::getError()));
            $this->loadModel('action')->create('testreport', $reportID, 'Opened');
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $reportID));
            return print(js::locate(inlink('view', "reportID=$reportID"), 'parent'));
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
            $tasks = $this->testtask->getProductTasks($productID, empty($objectID) ? 'all' : $task->branch, 'id_desc', null, $scopeAndStatus);
            foreach($tasks as $testTask)
            {
                if($testTask->build == 'trunk') continue;
                $taskPairs[$testTask->id] = $testTask->name;
            }
            if(empty($taskPairs)) return print(js::alert($this->lang->testreport->noTestTask) . js::locate('back'));

            if(empty($objectID))
            {
                $objectID  = key($taskPairs);
                $task      = $this->testtask->getById($objectID);
                $productID = $this->commonAction($task->product, 'product');
            }
            $this->view->taskPairs = $taskPairs;

            if($this->app->tab == 'execution') $this->execution->setMenu($task->execution);
            if($this->app->tab == 'project') $this->project->setMenu($task->project);
        }

        if(empty($objectID)) return print(js::alert($this->lang->testreport->noObjectID) . js::locate('back'));
        if($objectType == 'testtask')
        {
            if($productID != $task->product) return print(js::error($this->lang->error->accessDenied) . js::locate('back'));
            $productIdList[$productID] = $productID;

            $begin     = !empty($begin) ? date("Y-m-d", strtotime($begin)) : $task->begin;
            $end       = !empty($end) ? date("Y-m-d", strtotime($end)) : $task->end;
            $execution = $this->execution->getById($task->execution);
            $builds    = array();
            if($task->build == 'trunk')
            {
                echo js::alert($this->lang->testreport->errorTrunk);
                return print(js::locate('back'));
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
        elseif($objectType == 'execution' or $objectType == 'project')
        {
            $executionID = $this->commonAction($objectID, $objectType);
            if($executionID != $objectID) return print(js::error($this->lang->error->accessDenied) . js::locate('back'));

            $execution     = $this->execution->getById($executionID);
            $tasks         = $this->testtask->getExecutionTasks($executionID, $objectType);
            $task          = $objectID ? $this->testtask->getById($extra) : key($tasks);
            $owners        = array();
            $buildIdList   = array();
            $productIdList = array();
            foreach($tasks as $i => $testtask)
            {
                if(!empty($extra) and strpos(",{$extra},", ",{$testtask->id},") === false)
                {
                    unset($tasks[$i]);
                    continue;
                }

                $owners[$testtask->owner] = $testtask->owner;
                $productIdList[$testtask->product] = $testtask->product;
                $this->setChartDatas($testtask->id);
                if($testtask->build != 'trunk') $buildIdList[$testtask->build] = $testtask->build;
            }
            if(count($productIdList) > 1)
            {
                echo(js::alert($this->lang->testreport->moreProduct));
                return print(js::locate('back'));
            }

            if($this->app->tab == 'qa')
            {
                $productID = $this->product->saveState(key($productIdList), $this->products);
                $this->loadModel('qa')->setMenu($this->products, $productID);
            }
            elseif($this->app->tab == 'project')
            {
                $projects  = $this->project->getPairsByProgram();
                $projectID = $this->project->saveState($execution->id, $projects);
                $this->project->setMenu($projectID);
            }

            $builds  = $this->build->getByList($buildIdList);
            $stories = !empty($builds) ? $this->testreport->getStories4Test($builds) : $this->story->getExecutionStories($execution->id);;

            $begin = !empty($begin) ? date("Y-m-d", strtotime($begin)) : $task->begin;
            $end   = !empty($end) ? date("Y-m-d", strtotime($end)) : $task->end;
            $owner = current($owners);
            $bugs  = $this->testreport->getBugs4Test($builds, $productIdList, $begin, $end, 'execution');

            $this->view->title       = $execution->name . $this->lang->testreport->create;
            $this->view->reportTitle = date('Y-m-d') . ' ' . strtoupper($objectType) . "#{$execution->id} {$execution->name} {$this->lang->testreport->common}";
        }

        $cases = $this->testreport->getTaskCases($tasks, $begin, $end);

        list($bugInfo, $bugSummary) = $this->testreport->getBug4Report($tasks, $productIdList, $begin, $end, $builds);

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

        $caseList = array();
        foreach($cases as $taskID => $casesList)
        {
            foreach($casesList as $caseID => $case) $caseList[$caseID] = $case;
        }
        $perCaseResult = $this->testreport->getPerCaseResult4Report($tasks, array_keys($caseList), $begin, $end);
        $perCaseRunner = $this->testreport->getPerCaseRunner4Report($tasks, array_keys($caseList), $begin, $end);
        $this->view->datas['testTaskPerRunResult'] = $this->loadModel('report')->computePercent($perCaseResult);
        $this->view->datas['testTaskPerRunner']    = $this->report->computePercent($perCaseRunner);

        $this->view->bugInfo    = $bugInfo;
        $this->view->legacyBugs = $bugSummary['legacyBugs'];
        $this->view->bugSummary = $bugSummary;

        $this->view->objectID   = $objectID;
        $this->view->objectType = $objectType;
        $this->view->extra      = $extra;
        $this->display();
    }

    /**
     * Edit report
     *
     * @param  int       $reportID
     * @param  string    $begin
     * @param  string    $end
     * @access public
     * @return void
     */
    public function edit($reportID, $begin = '', $end ='')
    {
        if($_POST)
        {
            $changes = $this->testreport->update($reportID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $files      = $this->loadModel('file')->saveUpload('testreport', $reportID);
            $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
            $actionID   = $this->loadModel('action')->create('testreport', $reportID, 'Edited', $fileAction);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);

            return print(js::locate(inlink('view', "reportID=$reportID"), 'parent'));
        }

        $report    = $this->testreport->getById($reportID);
        $execution = $this->execution->getById($report->execution);
        $begin     = !empty($begin) ? date("Y-m-d", strtotime($begin)) : $report->begin;
        $end       = !empty($end) ? date("Y-m-d", strtotime($end)) : $report->end;

        if($this->app->tab == 'qa' and !empty($report->product))
        {
            $product   = $this->product->getById($report->product);
            $productID = $this->commonAction($report->product, 'product');
            if($productID != $report->product) return print(js::error($this->lang->error->accessDenied) . js::locate('back'));

            $browseLink = inlink('browse', "objectID=$productID&objectType=product");
            $this->view->position[] = html::a($browseLink, $product->name);
            $this->view->position[] = $this->lang->testreport->edit;
        }
        elseif($this->app->tab == 'execution' or $this->app->tab == 'project')
        {
            if($this->app->tab == 'execution')
            {
                $objectID = $this->commonAction($report->execution, 'execution');
                if($objectID != $report->execution) return print(js::error($this->lang->error->accessDenied) . js::locate('back'));
            }
            else
            {
                $objectID = $this->commonAction($report->project, 'project');
                if($objectID != $report->project) return print(js::error($this->lang->error->accessDenied) . js::locate('back'));
            }

            $browseLink = inlink('browse', "objectID=$objectID&objectType=execution");
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
                return print(js::locate('back'));
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
        elseif($report->objectType == 'execution' or $report->objectType == 'project')
        {
            $tasks = $this->testtask->getByList($report->tasks);
            $productIdList[$report->product] = $report->product;

            foreach($tasks as $task) $this->setChartDatas($task->id);

            $builds  = $this->build->getByList($report->builds);
            $stories = !empty($builds) ? $this->testreport->getStories4Test($builds) : $this->story->getExecutionStories($execution->id);;
            $bugs    = $this->testreport->getBugs4Test($builds, $productIdList, $begin, $end, 'execution');
        }

        $cases = $this->testreport->getTaskCases($tasks, $begin, $end);

        list($bugInfo, $bugSummary) = $this->testreport->getBug4Report($tasks, $productIdList, $begin, $end, $builds);

        $this->view->title = $report->title . $this->lang->testreport->edit;

        $this->view->report        = $report;
        $this->view->begin         = $begin;
        $this->view->end           = $end;
        $this->view->stories       = $stories;
        $this->view->bugs          = $bugs;
        $this->view->execution     = $execution;
        $this->view->productIdList = join(',', array_keys($productIdList));
        $this->view->tasks         = join(',', array_keys($tasks));
        $this->view->storySummary  = $this->product->summary($stories);

        $this->view->builds = $builds;
        $this->view->users  = $this->user->getPairs('noletter|noclosed|nodeleted');

        $this->view->cases       = $cases;
        $this->view->caseSummary = $this->testreport->getResultSummary($tasks, $cases, $begin, $end);

        $perCaseResult = $this->testreport->getPerCaseResult4Report($tasks, $report->cases, $begin, $end);
        $perCaseRunner = $this->testreport->getPerCaseRunner4Report($tasks, $report->cases, $begin, $end);
        $this->view->datas['testTaskPerRunResult'] = $this->loadModel('report')->computePercent($perCaseResult);
        $this->view->datas['testTaskPerRunner']    = $this->report->computePercent($perCaseRunner);

        $this->view->legacyBugs = $bugSummary['legacyBugs'];
        $this->view->bugInfo    = $bugInfo;
        $this->view->bugSummary = $bugSummary;

        $this->display();
    }

    /**
     * View report.
     *
     * @param  int    $reportID
     * @param  string $tab
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view($reportID, $tab = 'basic', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $reportID = (int)$reportID;
        $report   = $this->testreport->getById($reportID);
        if(!$report) return print(js::error($this->lang->notFound) . js::locate($this->createLink('qa', 'index')));
        $this->session->project = $report->project;

        $browseLink = '';
        $execution  = $this->execution->getById($report->execution);
        if($this->app->tab == 'qa' and !empty($report->product))
        {
            $product   = $this->product->getById($report->product);
            $productID = $this->commonAction($report->product, 'product');
            if($productID != $report->product) return print(js::error($this->lang->error->accessDenied) . js::locate('back'));
        }
        elseif($this->app->tab == 'execution' or $this->app->tab == 'project')
        {
            if($this->app->tab == 'execution')
            {
                $objectID = $this->commonAction($report->execution, 'execution');
                if($objectID != $report->execution) return print(js::error($this->lang->error->accessDenied) . js::locate('back'));
            }
            else
            {
                $objectID = $this->commonAction($report->project, 'project');
                if($objectID != $report->project) return print(js::error($this->lang->error->accessDenied) . js::locate('back'));
            }
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

        $tasks   = $report->tasks ? $this->testtask->getByList($report->tasks) : array();;
        $builds  = $report->builds ? $this->build->getByList($report->builds) : array();
        $cases   = $this->testreport->getTaskCases($tasks, $report->begin, $report->end, $report->cases);

        list($bugInfo, $bugSummary) = $this->testreport->getBug4Report($tasks, $report->product, $report->begin, $report->end, $builds);

        /* save session .*/
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

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
        $this->view->cases     = $this->testreport->getTaskCases($tasks, $report->begin, $report->end, $report->cases, $pager);
        $this->view->users     = $this->user->getPairs('noletter|noclosed|nodeleted');
        $this->view->actions   = $this->loadModel('action')->getList('testreport', $reportID);

        $this->view->storySummary = $this->product->summary($stories);
        $this->view->caseSummary  = $this->testreport->getResultSummary($tasks, $cases, $report->begin, $report->end);

        $perCaseResult = $this->testreport->getPerCaseResult4Report($tasks, $report->cases, $report->begin, $report->end);
        $perCaseRunner = $this->testreport->getPerCaseRunner4Report($tasks, $report->cases, $report->begin, $report->end);
        $this->view->datas['testTaskPerRunResult'] = $this->loadModel('report')->computePercent($perCaseResult);
        $this->view->datas['testTaskPerRunner']    = $this->report->computePercent($perCaseRunner);

        $this->view->bugInfo    = $bugInfo;
        $this->view->legacyBugs = $bugSummary['legacyBugs'];
        $this->view->bugSummary = $bugSummary;

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
            return print(js::confirm($this->lang->testreport->confirmDelete, inlink('delete', "reportID=$reportID&confirm=yes")));
        }
        else
        {
            $testreport = $this->testreport->getById($reportID);
            $locateLink = $this->session->reportList ? $this->session->reportList : inlink('browse', "productID={$testreport->product}");

            $this->testreport->delete(TABLE_TESTREPORT, $reportID);
            return print(js::locate($locateLink, 'parent'));
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
            $productID = $this->product->saveState($objectID, $this->products);
            $this->loadModel('qa')->setMenu($this->products, $productID);
            return $productID;
        }
        elseif($objectType == 'execution')
        {
            $executions  = $this->execution->getPairs();
            $executionID = $this->execution->saveState($objectID, $executions);
            $this->execution->setMenu($executionID);
            return $executionID;
        }
        elseif($objectType == 'project')
        {
            $projects  = $this->project->getPairsByProgram();
            $projectID = $this->project->saveState($objectID, $projects);
            $this->project->setMenu($projectID);
            return $projectID;
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
