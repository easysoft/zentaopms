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
     * 创建度量项。
     * Create a metric.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        unset($this->lang->metric->scopeList['other']);
        unset($this->lang->metric->purposeList['other']);
        unset($this->lang->metric->objectList['other']);
        unset($this->lang->metric->objectList['review']);

        if(!empty($_POST))
        {
            $metricData = $this->metricZen->buildMetricForCreate();
            $metricData->unit = isset($_POST['customUnit']) ? $_POST['addunit'] : $_POST['unit'];

            $metricID = $this->metric->create($metricData);

            if(empty($metricID) || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $response = $this->metricZen->responseAfterCreate($metricData->scope);

            return $this->send($response);
        }

        $this->metric->processObjectList();
        $this->metric->processUnitList();
        $this->display();
    }

    /**
     * 编辑度量项。
     * Edit a metric.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        unset($this->lang->metric->scopeList['other']);
        unset($this->lang->metric->purposeList['other']);
        unset($this->lang->metric->objectList['other']);
        unset($this->lang->metric->objectList['review']);

        $metric = $this->metric->getByID($id);

        if(!empty($_POST))
        {
            $metricData = $this->metricZen->buildMetricForEdit();
            $metricData->unit = isset($_POST['customUnit']) ? $_POST['addunit'] : $_POST['unit'];

            $metricID = $this->metric->update($id, $metricData);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $response = $this->metricZen->responseAfterEdit($metricData->scope);

            return $this->send($response);
        }

        $this->metric->processObjectList();
        $this->metric->processUnitList();

        $this->view->metric = $metric;
        $this->display();
    }

    /**
     * 度量项预览列表。
     * Preview metric list.
     *
     * @param  string $scope
     * @param  string $viewType
     * @param  int    $metricID
     * @param  string $filters
     * @access public
     * @return void
     */
    public function preview($scope = 'project', $viewType = 'single', $metricID = 0, $filtersBase64 = '')
    {
        $this->metric->processScopeList('released');

        if($scope == 'filter')
        {
            $filters = json_decode(base64_decode($filtersBase64), true);
            if(!is_array($filters)) $filters = array();
            $metrics = $this->metric->getListByFilter($filters, 'released');
        }
        elseif($scope == 'collect')
        {
            $metrics = $this->metric->getListByCollect('released');
        }
        else
        {
            $metrics = $this->metric->getList($scope, 'released');
        }
        $current = $this->metric->getByID($metricID);
        if(empty($current)) $current = current($metrics);

        $resultHeader = array();
        $resultData   = array();
        if(!empty($current))
        {
            $metric = $this->metric->getByID($current->id);
            $result = $this->metric->getResultByCode($metric->code, array(), 'cron');
            $resultHeader = $this->metricZen->getViewTableHeader($result);
            $resultData   = $this->metricZen->getViewTableData($metric, $result);
        }

        $this->view->metrics       = $metrics;
        $this->view->current       = $current;
        $this->view->metricList    = $this->lang->metric->metricList;
        $this->view->scope         = $scope;
        $this->view->title         = $this->lang->metric->common;
        $this->view->viewType      = $viewType;
        $this->view->recTotal      = count($metrics);
        $this->view->filters       = $filters;
        $this->view->filtersBase64 = $filtersBase64;
        $this->view->resultHeader  = $resultHeader;
        $this->view->resultData    = $resultData;
        $this->display();
    }

    /**
     * 获取度量项列表。
     * Get metric list by ajax.
     *
     * @param  string $scope
     * @param  string $filters
     * @access public
     * @return void
     */
    public function ajaxGetMetrics($scope, $filters)
    {
        if($scope == 'filter')
        {
            $filters = json_decode(base64_decode($filters), true);
            if(!is_array($filters)) $filters = array();
            $metrics = $this->metric->getListByFilter($filters, 'released');
        }
        elseif($scope == 'collect')
        {
            $metrics = $this->metric->getListByCollect('released');
        }
        else
        {
            $metrics = $this->metric->getList($scope, 'released');
        }

        echo(json_encode($metrics));
    }

    /**
     * 查看度量项列表。
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
    public function browse($scope = 'system', $stage = 'all', $param = 0, $type = 'bydefault', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        unset($this->config->metric->dtable->definition->fieldList['actions']['list']['delete']);
        $this->loadModel('search');
        $this->metric->processScopeList();

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = $type == 'bydefault' ? 0 : (int)$param;
        $actionURL = $this->createLink('metric', 'browse', "scope=$scope&stage=$stage&param=myQueryID&type=bysearch");
        $this->metric->buildSearchForm($queryID, $actionURL);

        $metrics = $this->metric->getList($scope, $stage, $param, $type, $queryID, $orderBy, $pager);
        $metrics = $this->metricZen->prepareActionPriv($metrics);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'metric', true);

        $modules    = $this->metric->getModuleTreeList($scope);
        $metricTree = $this->metricZen->prepareTree($scope, $stage, $modules);
        $scopeList  = $this->metricZen->prepareScopeList();

        $oldMetricPairs = array();
        foreach($metrics as $metric)
        {
            if($this->metric->isOldMetric($metric)) $oldMetricPairs[$metric->id] = $metric->fromID;
        }

        $this->view->title          = $this->lang->metric->common;
        $this->view->metrics        = $metrics;
        $this->view->oldMetricPairs = $oldMetricPairs;
        $this->view->pager          = $pager;
        $this->view->orderBy        = $orderBy;
        $this->view->param          = $param;
        $this->view->metricTree     = $metricTree;
        $this->view->closeLink      = $this->inlink('browse', 'scope=' . $scope);
        $this->view->type           = $type;
        $this->view->stage          = $stage;
        $this->view->scopeList      = $scopeList;
        $this->view->scope          = $scope;
        $this->view->scopeText      = $this->lang->metric->scopeList[$scope];

        $this->display();
    }

    /**
     * 查看度量项的详情。
     * View metric details.
     *
     * @param  int    $metricID
     * @access public
     * @return void
     */
    public function details($metricID)
    {
        $metric = $this->metric->getByID($metricID);

        $this->view->metric         = $metric;
        $this->view->legendBasic    = $this->metricZen->getBasicInfo($this->view,'scope,object,purpose,name,code,unit,desc,definition');
        $this->view->createEditInfo = $this->metricZen->getCreateEditInfo($this->view, 'createdBy,implementedBy,lastEdited');

        $this->display();
    }

    /**
     * 删除度量项。
     * View metric details.
     *
     * @param  int    $metricID
     * @access public
     * @return void
     */
    public function delete($metricID)
    {
        $this->dao->update(TABLE_METRIC)->set('deleted')->eq(1)->where('id')->eq($metricID)->exec();
        $locateLink = $this->createLink('metric', 'browse');
        return $this->send(array('result' => 'success', 'load' => $locateLink, 'closeModal' => true));
    }

    /**
     * 计算度量项。
     * Execute metric.
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

        if(dao::isError())
        {
            echo dao::getError();
            return false;
        }
        echo 'success';
    }

    /**
     * 度量项详情页。
     * View a metric.
     *
     * @param  int    $metricID
     * @access public
     * @return void
     */
    public function view(int $metricID)
    {
        $this->metric->processUnitList();

        $metric = $this->metric->getByID($metricID);
        $isOldMetric = $this->metric->isOldMetric($metric);
        if($isOldMetric) $measurement = $this->metric->getOldMetricByID($metric->fromID);

        if($_POST && $isOldMetric)
        {
            $result = $this->metric->createSqlFunction($measurement->configure, $measurement);
            if($result['result'] != 'success') return $this->send($result);

            foreach($this->post->varName as $i => $varName)
            {
                if(empty($varName)) return $this->send(array('result' => 'fail', 'errors' => $this->lang->metric->tips->noticeVarName));
                $params[$varName]['showName'] = zget($this->post->showName, $i, '');

                $errors = array();
                if($params[$varName]['showName'] == '') $errors[] = sprintf($this->lang->metric->tips->showNameMissed, $varName);
                if(empty($this->post->queryValue[$i]))  $errors[] = sprintf($this->lang->metric->tips->noticeQueryValue, $varName);

                if(!empty($errors)) return $this->send(array('result' => 'fail', 'errors' => join("<br>", $errors)));

                $params[$varName]['varName']      = $varName;
                $params[$varName]['varType']      = zget($this->post->varType, $i, 'input');
                $params[$varName]['showName']     = zget($this->post->showName, $i, '');
                $params[$varName]['options']      = $this->post->options[$i];
                $params[$varName]['defaultValue'] = zget($this->post->defaultValue, $i, '');
            }

            $this->dao->update(TABLE_BASICMEAS)
                ->set('configure')->eq($measurement->configure)
                ->set('params')->eq(json_encode($params))
                ->where('id')->eq($metric->fromID)
                ->exec();

            $params       = $this->metric->processPostParams();
            $measFunction = $this->metric->getSqlFunctionName($measurement);
            $queryResult  = $this->metric->execSqlMeasurement($measurement, $params);

            if($queryResult === false) return $this->send(array('result' => 'fail', 'message' => $this->metric->errorInfo));
            return $this->send(array('result' => 'success', 'queryResult' => sprintf($this->lang->metric->saveSqlMeasSuccess, $queryResult)));
        }

        $result = $this->metric->getResultByCode($metric->code, array(), 'cron');

        $this->view->title          = $metric->name;
        $this->view->metric         = $metric;
        $this->view->isOldMetric    = $isOldMetric;
        $this->view->isCalcExists   = $this->metric->checkCalcExists($metric);
        $this->view->result         = $result;
        $this->view->resultHeader   = $this->metricZen->getViewTableHeader($result);
        $this->view->resultData     = $this->metricZen->getViewTableData($metric, $result);
        $this->view->legendBasic    = $this->metricZen->getBasicInfo($this->view);
        $this->view->createEditInfo = $this->metricZen->getCreateEditInfo($this->view);
        $this->view->actions        = $this->loadModel('action')->getList('metric', $metricID);
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->preAndNext     = $this->loadModel('common')->getPreAndNextObject('metric', $metricID);
        if($metric->fromID !== 0) $this->view->oldMetricInfo = $this->metricZen->getOldMetricInfo($metric->fromID);

        if($isOldMetric)
        {
            $params = json_decode($measurement->params, true);
            $this->view->measurement = $measurement;
            $this->view->params      = empty($params) ? array() : json_decode($measurement->params, true);
        }

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

        $updateMetric = new stdclass();
        $updateMetric->id = $metric->id;

        $updateMetric->stage        = 'wait';
        $updateMetric->delistedBy   = $this->app->user->account;
        $updateMetric->delistedDate = helper::now();
        $this->metric->updateMetric($updateMetric);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $actionID = $this->loadModel('action')->create('metric', $metricID, 'delist', '', '', $this->app->user->account);

        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 度量项实现页面。
     * Implement a metric.
     *
     * @param  int  $metricID
     * @param  bool $isVerify
     * @access public
     * @return void
     */
    public function implement(int $metricID, bool $isVerify = false)
    {
        $metric = $this->metric->getByID($metricID);

        if($isVerify)
        {
            list($hasError, $verifyResult) = $this->metricZen->verifyCalc($metric);
            $result = !$hasError ? $this->metric->runCustomCalc($metric->code) : null;

            $this->view->metric       = $metric;
            $this->view->verifyResult = $verifyResult;
            $result = $this->metric->runCustomCalc($metric->code);
            $this->view->result       = $result;
            if($result)
            {
                $this->view->resultHeader = $this->metricZen->getViewTableHeader($result);
                $this->view->resultData   = $this->metricZen->getViewTableData($metric, $result);
            }
        }

        $this->metric->processImplementTips($metric->code);

        $this->view->metric = $metric;
        $this->display();
    }

    /**
     * 发布度量项。
     * Publish a metric.
     *
     * @param  int $metricID
     * @access public
     * @return void
     */
    public function publish($metricID)
    {
        $metric = $this->metric->getByID($metricID);

        $this->metric->moveCalcFile($metric);

        $publishedMetric = new stdclass();
        $publishedMetric->id              = $metricID;
        $publishedMetric->stage           = 'released';
        $publishedMetric->implementedBy   = $this->app->user->account;
        $publishedMetric->implementedDate = helper::now();
        $this->metric->updateMetric($publishedMetric);

        $this->loadModel('action')->create('metric', $metricID, 'publish', '', '', $this->app->user->account);

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
    }

    /**
     * 获取数据表格的数据。
     * Get data of datatable.
     *
     * @param  int $metricID
     * @access public
     * @return string
     */
    public function ajaxGetTableData($metricID)
    {
        $metric = $this->metric->getByID($metricID);
        $result = $this->metric->getResultByCode($metric->code, $_POST, 'cron');

        $response = new stdclass();
        $response->header = $this->metricZen->getViewTableHeader($result);
        $response->data   = $this->metricZen->getViewTableData($metric, $result);

        echo json_encode($response);
    }

    /**
     * 获取数据表格的数据。
     * Get data of datatable.
     *
     * @param  int $metricID
     * @access public
     * @return string
     */
    public function ajaxCollectMetric($metricID)
    {
        $metric = $this->metric->getByID($metricID);

        $isCollect = strpos($metric->collector, ',' . $this->app->user->account . ',') !== false;
        $collector = explode(',', $metric->collector);

        if($isCollect)
        {
            $key = array_search($this->app->user->account, $collector);
            if($key) unset($collector[$key]);
        }
        else
        {
            $collector[] = $this->app->user->account;
        }
        $collector = array_filter($collector);

        $metric = new stdclass();
        $metric->collector = ',' . implode(',', $collector) . ',';
        $this->metric->updateMetricFields($metricID, $metric);

        $response = new stdclass();
        $response->result  = 'success';
        $response->collect = !$isCollect;

        echo json_encode($response);
    }

    /**
     * 下载度量项模板文件。
     * Download metric template php file.
     *
     * @param  int $metricID
     * @access public
     * @return void
     */
    public function downloadTemplate(int $metricID)
    {
        list($fileName, $content) = $this->metric->getMetricPHPTemplate($metricID);

        $this->loadModel('file')->sendDownHeader($fileName, 'php', $content, 'content');
    }

    /**
     * 获取度量项默认值和测试值的的下拉选项。
     * Get options of default value and query value.
     *
     * @param  string $optionType
     * @access public
     * @return string
     */
    public function ajaxGetControlOptions($optionType)
    {
        $options = $this->metric->getControlOptions($optionType);

        $optionList = array();
        foreach($options as $value => $option) $optionList[] = array('value' => $value, 'text' => $option, 'keys' => $option);

        return $this->send($optionList);
    }
}
