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
     * Browse metric list.
     *
     * @param  int    $param
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($scope = 'global', $param = 0, $type = 'bydefault', $orderBy = 'id', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('search');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        /* Build the search form. */
        $queryID   = $type == 'bydefault' ? 0 : (int)$param;
        $actionURL = $this->createLink('metric', 'browse', "scope=$scope&param=myQueryID&type=bysearch");
        $this->metric->buildSearchForm($queryID, $actionURL);

        $metrics = $this->metric->getList($scope, $param, $type, $queryID, $sort, $pager);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'metric', true);

        $modules    = $this->metric->getModuleTreeList();
        $metricTree = $this->metricZen->prepareTree($scope, $modules);
        $scopeList  = $this->metricZen->prepareScopeList();

        $this->view->title       = $this->lang->metric->common;
        $this->view->metrics     = $metrics;
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;
        $this->view->param       = $param;
        $this->view->metricTree  = $metricTree;
        $this->view->closeLink   = $this->inlink('browse', 'scope=' . $scope);
        $this->view->type        = $type;
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
        $calcList = $this->metric->getCalcList();
        $classifiedCalcGroup = $this->metric->classifyCalc($calcList);

        foreach($classifiedCalcGroup as $calcGroup)
        {
            $rows = $this->metricZen->prepareDataset($calcGroup)->fetchAll();
            $this->metricZen->calcMetric($rows, $calcGroup->calcList);
        }

        $records = $this->metricZen->prepareMetricRecord($calcList);

        $this->metric->insertMetricLib($records);
    }

    /**
     * 查询度量项详情页。
     * View a metric.
     *
     * @param  int    $metricID
     * @access public
     * @return void
     */
    public function view(int $metricID)
    {
        $metric = $this->metric->getByID($metricID);
        $result = $this->metric->getResultByCode($metric->code);

        $this->view->title          = $metric->name;
        $this->view->metric         = $metric;
        $this->view->result         = $result;
        $this->view->resultHeader   = $this->metricZen->getResultHeader($result);
        $this->view->resultData     = $this->metricZen->getResultData($metric, $result);
        $this->view->legendBasic    = $this->metricZen->getBasicInfo($this->view);
        $this->view->createEditInfo = $this->metricZen->getCreateEditInfo($this->view);

        $this->display();
    }
}
