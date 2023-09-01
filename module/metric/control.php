<?php
/**
 * The control file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easycorp.ltd>
 * @package     metric
 * @version     $Id: control.php 5145 2013-07-15 06:47:26Z zhouxin@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class metric extends control
{
    /**
     * __construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a metric.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            $metricData = $this->metricZen->buildMetricForCreate();
            $metricID   = $this->metric->create($metricData);

            if(empty($metricID) || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $response = $this->metricZen->responseAfterCreate();

            return $this->send($response);
        }

        $this->metric->processObjectList();
        $this->metric->processUnitList();
        $this->display();
    }

    /**
     * Browse metric list.
     *
     * @param  string $scope
     * @param  string $stage
     * @param  int    $param
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($scope = 'system', $stage = 'all', $param = 0, $type = 'bydefault', $orderBy = 'id', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('search');
        $this->metric->processScopeList();

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        /* Build the search form. */
        $queryID   = $type == 'bydefault' ? 0 : (int)$param;
        $actionURL = $this->createLink('metric', 'browse', "scope=$scope&param=myQueryID&type=bysearch");
        $this->metric->buildSearchForm($queryID, $actionURL);

        $metrics = $this->metric->getList($scope, $stage, $param, $type, $queryID, $sort, $pager);
        $metrics = $this->metricZen->prepareActionPriv($metrics);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'metric', true);

        $modules    = $this->metric->getModuleTreeList($scope);
        $metricTree = $this->metricZen->prepareTree($scope, $stage, $modules);
        $scopeList  = $this->metricZen->prepareScopeList();

        $this->view->title       = $this->lang->metric->common;
        $this->view->metrics     = $metrics;
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;
        $this->view->param       = $param;
        $this->view->metricTree  = $metricTree;
        $this->view->closeLink   = $this->inlink('browse', 'scope=' . $scope);
        $this->view->type        = $type;
        $this->view->stage       = $stage;
        $this->view->scopeList   = $scopeList;
        $this->view->scope       = $scope;
        $this->view->scopeText   = $this->lang->metric->scopeList[$scope];

        $this->display();
    }

    /**
     * 计算度量项。
     * Excute metric.
     *
     * @access public
     * @return void
     */
    public function updateMetricLib()
    {
        $calcList = $this->metric->getCalcInstanceList();
        $classifiedCalcGroup = $this->metric->classifyCalc($calcList);

        foreach($classifiedCalcGroup as $calcGroup)
        {
            $statement = $this->metricZen->prepareDataset($calcGroup);
            if(empty($statement)) continue;

            $rows = $statement->fetchAll();
            $this->metricZen->calcMetric($rows, $calcGroup->calcList);

            $records = $this->metricZen->prepareMetricRecord($calcGroup->calcList);

            $this->metric->insertMetricLib($records);
        }
    }

    /**
     * 查询度量项详情页。
     * View a metric.
     *
     * @param  int    $metricID
     * @access public
     * @return void
     */
    public function view(int $metricID): void
    {
        $this->metric->processUnitList();
        $metric = $this->metric->getByID($metricID);
        $result = $this->metric->getResultByCode($metric->code);

        $this->view->title          = $metric->name;
        $this->view->metric         = $metric;
        $this->view->result         = $result;
        $this->view->resultHeader   = $this->metricZen->getResultHeader($result);
        $this->view->resultData     = $this->metricZen->getResultData($metric, $result);
        $this->view->legendBasic    = $this->metricZen->getBasicInfo($this->view);
        $this->view->createEditInfo = $this->metricZen->getCreateEditInfo($this->view);
        $this->view->actions        = $this->loadModel('action')->getList('metric', $metricID);
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->preAndNext     = $this->loadModel('common')->getPreAndNextObject('metric', $metricID);

        $this->display();
    }

    /**
     * 下架度量项。
     * Delist metric.
     *
     * @param  int $metricID
     * @access public
     * @return void
     */
    public function delist(int $metricID)
    {
        $metric = $this->metric->getByID($metricID);

        if(!$metric) return $this->send(array('result' => 'fail', 'message' => $this->lang->metric->notExist));

        $metric->stage = 'wait';
        $this->metric->update($metric);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'load' => true));
    }
}
