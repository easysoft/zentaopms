<?php
declare(strict_types=1);
/**
 * The control file of testreport of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testreport
 * @version     $Id$
 * @link        https://www.zentao.net
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
     * 构造函数。
     * Construct
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
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
        $tab      = $this->app->tab == 'project' || $this->app->tab == 'execution' ? $this->app->tab : 'qa';
        if(!isInModal())
        {
            if($tab == 'qa')
            {
                $products = $this->product->getPairs();
            }
            else
            {
                $objectID = $this->session->{$tab};
                $products = $this->product->getProducts($objectID, 'all', '', false);
            }
            if(empty($products) && !isInModal() && (helper::isAjaxRequest('zin') || helper::isAjaxRequest('fetch'))) $this->locate($this->createLink('product', 'showErrorNone', "moduleName={$tab}&activeMenu=testreport&objectID=$objectID"));
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

        $objectID = $this->testreportZen->commonAction($objectID, $objectType);
        $object   = $this->{$objectType}->getById($objectID);
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

            $reportData = $this->testreportZen->assignTesttaskReportData($objectID, $begin, $end, $productID, $task, 'create');
        }
        elseif($objectType == 'execution' || $objectType == 'project')
        {
            $executionID = $this->testreportZen->commonAction($objectID, $objectType);
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
     * @param  int    $reportID
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return void
     */
    public function edit(int $reportID, string $begin = '', string $end ='')
    {
        $oldReport = $this->testreport->getById($reportID);
        if($_POST)
        {
            $report  = $this->testreportZen->prepareTestreportForEdit($reportID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $changes = $this->testreport->update($report, $oldReport);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID   = $this->loadModel('action')->create('testreport', $reportID, 'Edited');
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "reportID=$reportID")));
        }

        if($this->app->tab == 'qa' && !empty($oldReport->product)) $objectType = 'product';
        if($this->app->tab == 'execution' || $this->app->tab == 'project') $objectType = $this->app->tab;
        if(isset($objectType))
        {
            $objectID = $this->testreportZen->commonAction($oldReport->{$objectType}, $objectType);
            if($objectID != $oldReport->{$objectType}) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied, 'load' => array('alert' => $this->lang->error->accessDenied, 'locate' => array('load' => true)))); ;
        }

        if($oldReport->objectType == 'testtask')
        {
            $task = $this->testtask->getByID($oldReport->objectID);
            if($task->build == 'trunk') return $this->send(array('result' => 'fail', 'message' => $this->lang->error->errorTrunk, 'load' => array('alert' => $this->lang->error->errorTrunk, 'locate' => array('load' => true)))); ;

            $reportData = $this->testreportZen->assignTesttaskReportData($oldReport->objectID, $begin, $end, $oldReport->product, $task, 'edit');
        }
        elseif($oldReport->objectType == 'execution' || $oldReport->objectType == 'project')
        {
            $reportData = $this->testreportZen->assignProjectReportDataForEdit($oldReport, $begin, $end);
        }
        $reportData['cases'] = $oldReport->cases;

        $this->testreportZen->assignReportData($reportData, 'edit');

        $this->view->title  = $oldReport->title . $this->lang->testreport->edit;
        $this->view->report = $oldReport;
        $this->display();
    }

    /**
     * 查看一个测试报告。
     * View a test report.
     *
     * @param  int    $reportID
     * @param  string $tab
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view(int $reportID, string $tab = 'basic', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        $report = $this->testreport->getById($reportID);
        if(!$report) return $this->send(array('result' => 'fail', 'message' => $this->lang->notFound, 'load' => array('alert' => $this->lang->notFound, 'locate' => array('load' => true))));

        /* Set session. */
        $this->session->project = $report->project;

        /* Check object type. */
        if($this->app->tab == 'qa' && !empty($report->product)) $objectType = 'product';
        if($this->app->tab == 'execution' || $this->app->tab == 'project') $objectType = $this->app->tab;

        if(isset($objectType))
        {
            $objectID = $this->testreportZen->commonAction($report->{$objectType}, $objectType);
            if($objectID != $report->{$objectType}) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied, 'load' => array('alert' => $this->lang->error->accessDenied, 'locate' => array('load' => true)))); ;
        }

        /* Get report data. */
        $reportData = $this->testreportZen->buildReportDataForView($report);

        if($report->objectType == 'testtask')
        {
            $this->testreportZen->setChartDatas($report->objectID);
        }
        elseif($reportData['tasks'])
        {
            foreach($reportData['tasks'] as $task) $this->testreportZen->setChartDatas($task->id);
        }

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Assign report data. */
        $this->testreportZen->assignReportData($reportData, 'view', $pager);

        $this->view->title      = $report->title;
        $this->view->browseLink = isset($objectType) ? inlink('browse', "objectID={$objectID}&objectType={$objectType}}") : '';
        $this->view->tab        = $tab;
        $this->view->actions    = $this->loadModel('action')->getList('testreport', $reportID);
        $this->display();
    }

    /**
     * 删除一个测试报告。
     * Delete report.
     *
     * @param  int    $reportID
     * @access public
     * @return void
     */
    public function delete(int $reportID)
    {
        $testreport = $this->testreport->getByID($reportID);
        $locateLink = $this->session->reportList ? $this->session->reportList : inlink('browse', "productID={$testreport->product}");

        $this->testreport->delete(TABLE_TESTREPORT, $reportID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $locateLink));
    }
}
