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
     * 浏览测试报告。
     * Browse report.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  int    $extra
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $objectID = 0, string $objectType = 'product', int $extra = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        if(strpos('product|execution|project', $objectType) === false) return $this->send(array('result' => 'fail', 'message' => 'Type Error!'));

        $objectID = $this->commonAction($objectID, $objectType);
        $object   = $this->$objectType->getById($objectID);
        if($extra) $task = $this->testtask->getByID($extra);

        $reports = $this->testreportZen->getReportsForBrowse($objectID, $objectType, $extra, $orderBy, $recTotal, $recPerPage, $pageID);
        if(empty($reports) && common::hasPriv('testreport', 'create'))
        {
            $param = '';
            if($objectType == 'product' && $extra) $param = "objectID={$extra}&objectType=testtask";
            if(strpos('|project|execution|', $objectType) !== false && ($extra || !empty($_POST['taskIdList'])))
            {
                $param  = "objectID={$objectID}&objectType={$objectType}";
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

        $this->view->title        = ($extra ? $task->name : $object->name) . $this->lang->colon . $this->lang->testreport->common;
        $this->view->reports      = $reports;
        $this->view->orderBy      = $orderBy;
        $this->view->objectID     = $objectID;
        $this->view->objectType   = $objectType;
        $this->view->object       = $object;
        $this->view->extra        = $extra;
        $this->view->users        = $this->user->getPairs('noletter|noclosed|nodeleted');
        $this->view->tasks        = $tasks ? $this->loadModel('testtask')->getPairsByList($tasks) : array();
        $this->view->executions   = $executions ? $this->loadModel('execution')->getPairsByList($executions) : array();
        $this->view->canBeChanged = common::canModify($objectType, $object); // Determines whether an object is editable.
        $this->display();
    }

    /**
     * 创建一个测试报告。
     * Create a report.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  int    $extra
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return void
     */
    public function create(int $objectID = 0, string $objectType = 'testtask', int $extra = 0, string $begin = '', string $end = '')
    {
        if($_POST)
        {
            $testreport = $this->testreportZen->prepareTestreportForCreate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $reportID = $this->testreport->create($testreport);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('testreport', $reportID, 'Opened');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "reportID={$reportID}"), 'id' => $reportID));
        }

        if($objectType == 'testtask') list($objectID, $task, $productID) = $this->testreportZen->assignTaskParisForCreate($objectID, $extra);

        if(!$objectID) return $this->send(array('result' => 'fail', 'load' => array('confirm' => $this->lang->testreport->noObjectID, 'confirmed' => inlink('browse', "proudctID={$productID}"), 'canceled' => inlink('browse', "proudctID={$productID}"))));

        if($objectType == 'testtask')
        {
            if($productID != $task->product) return $this->send(array('result' => 'fail', 'load' => array('confirm' => $this->lang->error->accessDenied, 'confirmed' => inlink('browse', "proudctID={$productID}"), 'canceled' => inlink('browse', "proudctID={$productID}"))));
            if($task->build == 'trunk')      return $this->send(array('result' => 'fail', 'load' => array('confirm' => $this->lang->testreport->errorTrunk, 'confirmed' => inlink('browse', "proudctID={$productID}"), 'canceled' => inlink('browse', "proudctID={$productID}"))));

            $reportData = $this->testreportZen->assignTesttaskReportDataForCreate($objectID, $begin, $end, $productID, $task);
        }
        elseif($objectType == 'execution' || $objectType == 'project')
        {
            $executionID = $this->commonAction($objectID, $objectType);
            if($executionID != $objectID) return $this->send(array('result' => 'fail', 'load' => array('confirm' => $this->lang->error->accessDenied, 'confirmed' => inlink('browse', "proudctID={$productID}"), 'canceled' => inlink('browse', "proudctID={$productID}"))));

            $reportData = $this->testreportZen->assignProjectReportDataForCreate($objectID, $objectType, $extra, $begin, $end, $executionID);

            if(count($reportData['productIdList']) > 1) return $this->send(array('result' => 'fail', 'load' => array('confirm' => $this->lang->testreport->moreProduct, 'confirmed' => inlink('browse', "proudctID={$productID}"), 'canceled' => inlink('browse', "proudctID=$productID"))));
        }

        $this->testreportZen->assignReportData($reportData, 'create');

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
            if(dao::isError()) return $this->send(array('result' => 'success', 'message' => dao::getError()));

            $files      = $this->loadModel('file')->saveUpload('testreport', $reportID);
            $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
            $actionID   = $this->loadModel('action')->create('testreport', $reportID, 'Edited', $fileAction);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "reportID=$reportID")));
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
        }

        if($report->objectType == 'testtask')
        {
            $productIdList[$report->product] = $report->product;

            $task      = $this->testtask->getByID($report->objectID);
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

        $caseList = array();
        foreach($cases as $taskID => $casesList)
        {
            foreach($casesList as $caseID => $case) $caseList[$caseID] = $case;
        }
        $this->view->caseList = $caseList;

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

        $cases    = $this->testreport->getTaskCases($tasks, $report->begin, $report->end, $report->cases, $pager);
        $caseList = array();
        foreach($cases as $taskID => $casesList)
        {
            foreach($casesList as $caseID => $case) $caseList[$caseID] = $case;
        }

        $this->view->title      = $report->title;
        $this->view->browseLink = $browseLink;

        $this->view->tab       = $tab;
        $this->view->pager     = $pager;
        $this->view->report    = $report;
        $this->view->execution = $execution;
        $this->view->stories   = $stories;
        $this->view->bugs      = $report->bugs ? $this->bug->getByIdList($report->bugs) : array();
        $this->view->builds    = $builds;
        $this->view->caseList  = $caseList;
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
            $productID = $this->product->checkAccess($objectID, $this->products);
            $this->loadModel('qa')->setMenu($productID);
            return $productID;
        }
        elseif($objectType == 'execution')
        {
            $executions  = $this->execution->getPairs();
            $executionID = $this->execution->checkAccess($objectID, $executions);
            $this->execution->setMenu($executionID);
            return $executionID;
        }
        elseif($objectType == 'project')
        {
            $projects  = $this->project->getPairsByProgram();
            $projectID = $this->project->checkAccess($objectID, $projects);
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
        $task   = $this->loadModel('testtask')->getByID($taskID);
        foreach($this->lang->testtask->report->charts as $chart => $title)
        {
            if(strpos($chart, 'testTask') === false) continue;

            $chartFunc   = 'getDataOf' . $chart;
            $chartData   = $this->testtask->$chartFunc($taskID);
            $chartOption = $this->testtask->mergeChartOption($chart);
            if(isset($chartType) && !empty($chartType)) $chartOption->type = $chartType;

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
