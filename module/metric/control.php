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
    public function browse($param = 0, $type = 'bydefault', $orderBy = 'id desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('search');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        /* Build the search form. */
        $queryID   = $type == 'bydefault' ? 0 : (int)$param;
        $actionURL = $this->createLink('metric', 'browse', "param=myQueryID&type=bysearch");
        $this->metric->buildSearchForm($queryID, $actionURL);

        $metrics = $this->metric->getList($type, $queryID, $sort, $pager);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'metric', true);

        $this->view->title       = $this->lang->metric->common;
        $this->view->metrics     = $metrics;
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;
        $this->view->param       = $param;
        $this->view->type        = $type;

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
}
