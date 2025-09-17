<?php
class screenTest
{

    public $objectModel;
    public $componentList = array();

    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('screen');
         $this->objectTao   = $tester->loadTao('screen');
         // Skip initScreen for __constructTest to avoid SQL errors
    }

    /**
     * 测试getList。
     * Test getList.
     *
     * @param  int   $dimensionID 维度ID。
     * @return array
     */
    public function getListTest(int $dimensionID): array
    {
        return $this->objectModel->getList($dimensionID);
    }

    /**
     * Test filterMetricData method.
     *
     * @param  array  $data
     * @param  string $dateType
     * @param  bool   $isObjectMetric
     * @param  array  $filters
     * @access public
     * @return array
     */
    public function filterMetricDataTest($data, $dateType, $isObjectMetric, $filters = array())
    {
        $result = $this->objectModel->filterMetricData($data, $dateType, $isObjectMetric, $filters);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试getByID。
     * Test getByID.
     *
     * @param  int         $screenID
     * @param  int         $year
     * @param  int         $dept
     * @param  string      $account
     * @return object|bool
     * @access public
     * @return object|bool
     */
    public function getByIDTest(int $screenID, int $year = 0, int $month = 0, int $dept = 0, string $account = '')
    {
        $this->objectModel->dao->update(TABLE_SCREEN)->set('scheme')->eq('')->where('id')->eq($screenID)->exec();
        return $this->objectModel->getByID($screenID, $year, $month, $dept, $account);
    }

    /**
     * 初始化大屏数据。
     * Initialize screen data.
     *
     * @access public
     * @return void
     */
    public function initScreen(): void
    {
        global $tester,$app;
        $appPath = $app->getAppRoot();
        $sqlFile = $appPath . 'test/data/screen.sql';
        $tester->dbh->exec(file_get_contents($sqlFile));
        $pivotFile = $appPath . 'test/data/pivot.sql';
        $tester->dbh->exec(file_get_contents($pivotFile));
        $chartFile = $appPath . 'test/data/chart.sql';
        $tester->dbh->exec(file_get_contents($chartFile));
    }

    /**
     * 获取所有组件。
     * Get all components.
     *
     * @param  array $filters
     * @access public
     * @return array
     */
    public function getAllComponent(array $filters = array(), bool $bultion = false)
    {
        if(!empty($this->componentList))
        {
            $componentList = $this->componentList;
        }
        else
        {
            global $tester;
            $sql = "SELECT * FROM `zt_screen`";
            $screenList = $tester->dbh->query($sql)->fetchAll();
            $componentList = array();
            foreach($screenList as $screen)
            {
                $componentList_ = array();
                if(!in_array($screen->id, array(3, 5, 6, 8)))
                {

                    $scheme = json_decode($screen->scheme);
                    $componentList_ = $scheme->componentList;
                }
                else
                {
                    $scheme = json_decode($screen->scheme);
                    if($scheme) $componentList_ =  $scheme->componentList;
                }

                foreach($componentList_ as $component)
                {
                    if($component->isGroup)
                    {
                        $componentList = array_merge($componentList, $component->groupList);
                    }
                    else
                    {
                        $componentList[] = $component;
                    }
                }
            }
            $this->componentList = $componentList;
        }

        return !empty($filters) ? array_filter($componentList, function($component)use($filters){
            foreach($filters as $field => $value)
            {
                if(isset($component->$field) && $component->$field == $value) return true;
            }

            return false;
        }) : $componentList;
    }

    /**
     * 补充组件信息。
     * Supplement component information.
     *
     * @param  object $component
     * @access public
     * @return array
     */
    public function completeComponent($component): array
    {
        if(isset($component->key) && $component->key === 'Select') return array(null, $component);
        $chartID = zget($component->chartConfig, 'sourceID', '');
        if(!$chartID) return array(null, $component);

        global $tester;
        $type  = $component->chartConfig->package == 'Tables' ? 'pivot' : 'chart';
        $table = $type == 'chart' ? TABLE_CHART : TABLE_PIVOT;
        $chart = $tester->dao->select('*')->from($table)->where('id')->eq($chartID)->fetch();
        return array($chart, $component);
    }

    /**
     * 测试completeComponent。
     * Test completeComponent.
     *
     * @param  object $chart
     * @param  string $type
     * @param  array  $filters
     * @param  object $component
     * @access public
     * @return void
     */
    public function completeComponentTest(object $chart, string $type, array $filters, object $component): void
    {
        $this->objectModel->completeComponent($chart, $type, $filters, $component);
    }

    /**
     * 测试setIsQueryScreenFilters。
     * Test setIsQueryScreenFilters.
     *
     * @param  array $filters
     * @access public
     * @return void
     */
    public function setIsQueryScreenFiltersTest(array &$filters): void
    {
        $this->objectModel->setIsQueryScreenFilters($filters);
    }

    /**
     * 测试setDefaultByDate。
     * Test setDefaultByDate.
     *
     * @param  array $filters
     * @access public
     * @return void
     */
    public function setDefaultByDateTest(array &$filters): void
    {
        $this->objectModel->setDefaultByDate($filters);
    }

    /**
     * 测试getChartOption。
     * Test getChartOption.
     *
     * @param  object $chart
     * @param  object $component
     * @access public
     * @return void
     */
    public function getChartOptionTest(object $chart, object $component): void
    {
        $component->option->dataset = new stdclass();
        $this->objectModel->getChartOption($chart, $component, array());
    }

    /**
     * 测试getChartFilters。
     * Test getChartFilters.
     *
     * @param  object $chart
     * @access public
     * @return void
     */
    public function getChartFiltersTest(object $chart): array
    {
        $filters = $this->objectModel->getChartFilters($chart);
        return json_decode(json_encode($filters), true);
    }

    /**
     * 测试getChartFilters。
     * Test getChartFilters.
     *
     * @param  array $xlabel
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @access public
     * @return array
     */
    public function processXlabelTest(array $xlabel, string $type, string $object, string $field): array
    {
        return $this->objectModel->processXlabel($xlabel, $type, $object, $field);
    }


    /**
     * 测试getSysOptions。
     * Test getSysOptions.
     *
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getSysOptionsTest(string $type, string $object, string $field, string $sql): array
    {
        return $this->objectModel->getSysOptions($type, $object, $field, $sql);
    }

    /**
     * 测试buildComponentList。
     * Test buildComponentList.
     *
     * @param  array|object $componentList
     * @access public
     * @return array
     */
    public function buildComponentListTest($componentList)
    {
        return $this->objectModel->buildComponentList($componentList);
    }

    /**
     * 测试buildComponent。
     * Test buildComponent.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function buildComponentTest(object $component): void
    {
        $this->objectModel->buildComponent($component);
    }

    /**
     * 测试buildChart。
     * Test buildChart.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function buildChartTest(object $component)
    {
        $this->objectModel->buildChart($component);
    }


    /**
     * 测试filterChart。
     * Test filterChart.
     *
     * @param  object $chart
     * @param  string $type
     * @param  bool   $filters
     * @access public
     * @return void
     */
    public function setFilterSqlTest(object $chart, string $type, bool $inCharts = false)
    {
        if(!$inCharts)
        {
            return $this->objectModel->setFilterSql($chart);
        }
        else
        {
            $this->objectModel->filter->charts[1018] = array('year' => '2023', 'dept' => '1', 'account' => 'admin');
            if($type !== 'account')
            {
                $this->objectModel->filter->year = '2023';
                $this->objectModel->filter->dept = '1';
                return $this->objectModel->setFilterSql($chart);
            }
            else
            {
                $this->objectModel->filter->account = 'admin';
                return $this->objectModel->setFilterSql($chart);
            }
        }
    }

    /**
     * 测试getLatestChart。
     * Test getLatestChart.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function getLatestChartTest(object $component): void
    {
        $this->objectModel->getLatestChart($component);
    }

    /**
     * 测试genComponentData。
     * Test genComponentData.
     *
     * @param  object $chart
     * @param  object $component
     * @param  string $type
     * @param  array  $filter
     * @access public
     * @return void
     */
    public function genComponentDataTest(object $chart, object $component, string $type, array $filter): void
    {
        $this->objectModel->genComponentData($chart, $type, $component, $filter);
    }

    /**
     * 测试genFilterCommponent。
     * Test genFilterComponent.
     *
     * @param  string $filterType
     * @access public
     * @return array
     */
    public function genFilterComponentTest(string $filterType): array
    {
        $component = $this->objectModel->genFilterComponent($filterType);

        return !empty($component->chartConfig->objectList) ? $component->chartConfig->objectList : array();
    }

    /**
     * 测试getBurnData。
     * Test getBurnData.
     *
     * @access public
     * @return array
     */
    public function getBurnDataTest(): array
    {
        return $this->objectModel->getBurnData();
    }

    /**
     * 测试initComponent。
     * Test initComponent.
     *
     * @param  object $chart
     * @param  string $type
     * @param  object $compoent
     * @access public
     * @return void
     */
    public function initComponentTest(object $chart, string $type, object $compoent): void
    {
        $this->objectModel->initComponent($chart, $type, $compoent);
    }

    /**
     * 测试setChartDefault。
     * Test setChartDefault.
     *
     * @param  string $type
     * @param  object $component
     * @access public
     * @return void
     */
    public function setChartDefaultTest(string $type, object $component): void
    {
        $this->objectModel->setChartDefault($type, $component);
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->objectModel, $method), $args);
    }

    /**
     * 测试genChartData。
     * Test genChartData.
     *
     * @param  object $screen
     * @param  int    $year
     * @param  int    $dept
     * @param  string $account
     * @access public
     * @return array
     */
    public function genChartDataTest(object $screen, int $year = 0, int $month = 0, int $dept = 0, string $account = ''): array
    {
        $chartData = $this->objectModel->genChartData($screen, $year, $month, $dept, $account);
        $filter = $this->objectModel->filter;

        return array($chartData, $filter);
    }

    /**
     * 测试buildSelect。
     * Test buildSelect.
     *
     * @param  object $component
     * @param  string $year
     * @param  string $dept
     * @param  string $account
     * @access public
     * @return object
     */
    public function buildSelectTest(object $component, string $year = '', string $dept = '', string $account = ''): object
    {
        if($year)    $this->objectModel->filter->year    = $year;
        if($dept)    $this->objectModel->filter->dept    = $dept;
        if($account) $this->objectModel->filter->account = $account;
        $this->objectModel->buildSelect($component, $year, $dept, $account);

        return $this->objectModel->filter;
    }

    /**
     *  Test set value by path.
     *
     * @param  object $option
     * @param  string $path
     * @param  string $value
     * @access public
     * @return void
     */
    public function setValueByPathTest(&$option, $path, $value)
    {
        $this->objectModel->setValueByPath($option, $path, $value);
    }

    /**
     * Test __construct method.
     *
     * @access public
     * @return array
     */
    public function __constructTest(): array
    {
        global $tester;
        
        // Create a new instance to test constructor
        $screenModel = $tester->loadModel('screen');
        
        $result = array();
        
        // Check if object type is correct
        $result['objectType'] = get_class($screenModel) === 'screenModel' ? 1 : 0;
        
        // Check parent initialization
        $result['parentInit'] = property_exists($screenModel, 'app') && property_exists($screenModel, 'dao') ? 1 : 0;
        
        // Check if BI DAO is loaded (dao property should exist after loadBIDAO)
        $result['biDAOLoaded'] = property_exists($screenModel, 'dao') ? 1 : 0;
        
        // Check if bi model is loaded  
        $result['biModelLoaded'] = property_exists($screenModel, 'bi') && is_object($screenModel->bi) ? 1 : 0;
        
        // Check if filter object is initialized
        $result['filterExists'] = property_exists($screenModel, 'filter') && is_object($screenModel->filter) ? 1 : 0;
        
        // Check filter properties initialization
        if($result['filterExists'])
        {
            $result['filterScreen']  = property_exists($screenModel->filter, 'screen') && $screenModel->filter->screen === '' ? 1 : 0;
            $result['filterYear']    = property_exists($screenModel->filter, 'year') && $screenModel->filter->year === '' ? 1 : 0;
            $result['filterMonth']   = property_exists($screenModel->filter, 'month') && $screenModel->filter->month === '' ? 1 : 0;
            $result['filterDept']    = property_exists($screenModel->filter, 'dept') && $screenModel->filter->dept === '' ? 1 : 0;
            $result['filterAccount'] = property_exists($screenModel->filter, 'account') && $screenModel->filter->account === '' ? 1 : 0;
            $result['filterCharts']  = property_exists($screenModel->filter, 'charts') && is_array($screenModel->filter->charts) ? 1 : 0;
        }
        else
        {
            $result['filterScreen']  = 0;
            $result['filterYear']    = 0;
            $result['filterMonth']   = 0;
            $result['filterDept']    = 0;
            $result['filterAccount'] = 0;
            $result['filterCharts']  = 0;
        }
        
        return $result;
    }

    /**
     * 初始化过滤条件。
     * Initialize filter conditions.
     *
     * @access public
     * @return void
     */
    public function initFilter(): void
    {
        $this->objectModel->filter = new stdclass();
        $this->objectModel->filter->screen  = '';
        $this->objectModel->filter->year    = '';
        $this->objectModel->filter->dept    = '';
        $this->objectModel->filter->account = '';
        $this->objectModel->filter->charts  = array();
    }

    /**
     * Test checkAccess method.
     *
     * @param  int $screenID
     * @access public
     * @return mixed
     */
    public function checkAccessTest(int $screenID): mixed
    {
        try {
            $result = $this->objectModel->checkAccess($screenID);
            if(dao::isError()) return dao::getError();
            return $result;
        } catch (Exception $e) {
            return $e->getMessage();
        } catch (EndResponseException $e) {
            // 这表示访问被拒绝或发生了重定向
            return 'access_denied';
        }
    }

    /**
     * Test mergeChartAndPivotFilters method.
     *
     * @param  string $type
     * @param  object $chartOrPivot
     * @param  int    $sourceID
     * @param  array  $filters
     * @access public
     * @return array
     */
    public function mergeChartAndPivotFiltersTest($type, $chartOrPivot, $sourceID, $filters)
    {
        $result = $this->objectModel->mergeChartAndPivotFilters($type, $chartOrPivot, $sourceID, $filters);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateComponentFilters method.
     *
     * @param  object $component
     * @param  array  $latestFilters
     * @access public
     * @return object
     */
    public function updateComponentFiltersTest($component, $latestFilters)
    {
        $result = $this->objectModel->updateComponentFilters($component, $latestFilters);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isFilterChange method.
     *
     * @param  mixed $oldFilters
     * @param  mixed $latestFilters
     * @access public
     * @return bool
     */
    public function isFilterChangeTest($oldFilters = null, $latestFilters = null)
    {
        $result = $this->objectModel->isFilterChange($oldFilters, $latestFilters);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test genNotFoundOrDraftComponentOption method.
     *
     * @param  object|null $component
     * @param  object|null $chart
     * @param  string      $type
     * @access public
     * @return array
     */
    public function genNotFoundOrDraftComponentOptionTest($component, $chart, $type)
    {
        $result = $this->objectModel->genNotFoundOrDraftComponentOption($component, $chart, $type);
        if(dao::isError()) return dao::getError();

        // 转换为数组格式便于测试
        $testResult = array();
        $testResult['hasOption'] = isset($result->option) ? 1 : 0;
        $testResult['hasTitle'] = isset($result->option->title) ? 1 : 0;
        $testResult['hasNotFoundText'] = isset($result->option->title->notFoundText) ? 1 : 0;
        $testResult['isDeleted'] = isset($result->option->isDeleted) && $result->option->isDeleted ? 1 : 0;
        $testResult['notFoundText'] = isset($result->option->title->notFoundText) ? $result->option->title->notFoundText : '';
        
        return $testResult;
    }

    /**
     * Test genDelistOrDeletedMetricOption method.
     *
     * @param  object|null $component
     * @access public
     * @return array
     */
    public function genDelistOrDeletedMetricOptionTest($component = null)
    {
        $result = $this->objectModel->genDelistOrDeletedMetricOption($component);
        if(dao::isError()) return dao::getError();

        // 转换为数组格式便于测试
        $testResult = array();
        $testResult['hasOption'] = isset($result->option) ? 1 : 0;
        $testResult['hasTitle'] = isset($result->option->title) ? 1 : 0;
        $testResult['hasNotFoundText'] = isset($result->option->title->notFoundText) ? 1 : 0;
        $testResult['isDeleted'] = isset($result->option->isDeleted) && $result->option->isDeleted ? 1 : 0;
        $testResult['notFoundText'] = isset($result->option->title->notFoundText) ? $result->option->title->notFoundText : '';
        
        return $testResult;
    }

    /**
     * Test unsetComponentDraftMarker method.
     *
     * @param  object $component
     * @access public
     * @return object
     */
    public function unsetComponentDraftMarkerTest($component)
    {
        $result = $this->objectModel->unsetComponentDraftMarker($component);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test genMetricComponent method.
     *
     * @param  int         $metricID
     * @param  object|null $component
     * @param  array       $filterParams
     * @access public
     * @return array
     */
    public function genMetricComponentTest($metricID, $component = null, $filterParams = array())
    {
        global $tester;
        
        // 从数据库获取metric对象
        $metric = $tester->dao->select('*')->from(TABLE_METRIC)->where('id')->eq($metricID)->fetch();
        if(empty($metric))
        {
            return array('hasComponent' => 0, 'isDeleted' => 0, 'isWaiting' => 0);
        }
        
        $result = $this->objectModel->genMetricComponent($metric, $component, $filterParams);
        if(dao::isError()) return dao::getError();

        // 转换为数组格式便于测试
        $testResult = array();
        $testResult['hasComponent'] = isset($result) && is_object($result) ? 1 : 0;
        $testResult['isDeleted'] = (isset($result->option->isDeleted) && $result->option->isDeleted) ? 1 : 0;
        $testResult['isWaiting'] = ($metric->stage == 'wait') ? 1 : 0;
        
        return $testResult;
    }

    /**
     * Test getMetricPagination method.
     *
     * @param  mixed $component
     * @access public
     * @return array
     */
    public function getMetricPaginationTest($component = null)
    {
        $result = $this->objectModel->getMetricPagination($component);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test preparePaginationBeforeFetchRecords method.
     *
     * @param  mixed $pagination
     * @access public
     * @return mixed
     */
    public function preparePaginationBeforeFetchRecordsTest($pagination)
    {
        $result = $this->objectModel->preparePaginationBeforeFetchRecords($pagination);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateMetricFilters method - Test case 1: component without filters
     *
     * @access public
     * @return array
     */
    public function updateMetricFiltersTest1()
    {
        $component = new stdclass();
        $component->chartConfig = new stdclass();
        
        $latestFilters = array('status' => 'enabled', 'filter1' => 'value1');
        
        $result = $this->objectModel->updateMetricFilters($component, $latestFilters);
        if(dao::isError()) return dao::getError();

        $testResult = array();
        $testResult['chartConfig'] = isset($result->chartConfig) ? 'present' : 'missing';
        $testResult['filters'] = isset($result->chartConfig->filters) ? $result->chartConfig->filters : array();
        $testResult['status'] = isset($result->chartConfig->filters['status']) ? $result->chartConfig->filters['status'] : 'missing';
        
        return $testResult;
    }

    /**
     * Test updateMetricFilters method - Test case 2: component with existing filters
     *
     * @access public
     * @return array
     */
    public function updateMetricFiltersTest2()
    {
        $component = new stdclass();
        $component->chartConfig = new stdclass();
        $component->chartConfig->filters = array('existing' => 'existingFilter', 'preserved' => true);
        
        $latestFilters = array('new' => 'newFilter');
        
        $result = $this->objectModel->updateMetricFilters($component, $latestFilters);
        if(dao::isError()) return dao::getError();

        $testResult = array();
        $testResult['chartConfig'] = isset($result->chartConfig) ? 'present' : 'missing';
        $testResult['filters'] = isset($result->chartConfig->filters) ? $result->chartConfig->filters : array();
        $testResult['existing'] = isset($result->chartConfig->filters['existing']) ? $result->chartConfig->filters['existing'] : 'missing';
        
        return $testResult;
    }

    /**
     * Test updateMetricFilters method - Test case 3: empty filters array
     *
     * @access public
     * @return array
     */
    public function updateMetricFiltersTest3()
    {
        $component = new stdclass();
        $component->chartConfig = new stdclass();
        
        $latestFilters = array();
        
        $result = $this->objectModel->updateMetricFilters($component, $latestFilters);
        if(dao::isError()) return dao::getError();

        $testResult = array();
        $testResult['chartConfig'] = isset($result->chartConfig) ? 'present' : 'missing';
        $testResult['filters'] = isset($result->chartConfig->filters) ? count($result->chartConfig->filters) : -1;
        
        return $testResult;
    }

    /**
     * Test updateMetricFilters method - Test case 4: complex filters object
     *
     * @access public
     * @return array
     */
    public function updateMetricFiltersTest4()
    {
        $component = new stdclass();
        $component->chartConfig = new stdclass();
        
        $latestFilters = array(
            'field1' => 'value1',
            'field2' => 'value2',
            'nested' => array('subfield' => 'subvalue')
        );
        
        $result = $this->objectModel->updateMetricFilters($component, $latestFilters);
        if(dao::isError()) return dao::getError();

        $testResult = array();
        $testResult['chartConfig'] = isset($result->chartConfig) ? 'present' : 'missing';
        $testResult['filters'] = isset($result->chartConfig->filters) ? $result->chartConfig->filters : array();
        $testResult['field1'] = isset($result->chartConfig->filters['field1']) ? $result->chartConfig->filters['field1'] : 'missing';
        $testResult['field2'] = isset($result->chartConfig->filters['field2']) ? $result->chartConfig->filters['field2'] : 'missing';
        
        return $testResult;
    }

    /**
     * Test updateMetricFilters method - Test case 5: component with chartConfig but no filters
     *
     * @access public
     * @return array
     */
    public function updateMetricFiltersTest5()
    {
        $component = new stdclass();
        $component->chartConfig = new stdclass();
        // chartConfig exists but no filters property
        
        $latestFilters = array('newField' => 'newValue');
        
        $result = $this->objectModel->updateMetricFilters($component, $latestFilters);
        if(dao::isError()) return dao::getError();

        $testResult = array();
        $testResult['chartConfig'] = isset($result->chartConfig) ? 'present' : 'missing';
        $testResult['filters'] = isset($result->chartConfig->filters) ? $result->chartConfig->filters : array();
        $testResult['newField'] = isset($result->chartConfig->filters['newField']) ? $result->chartConfig->filters['newField'] : 'missing';
        
        return $testResult;
    }

    /**
     * Test prepareRadarDataset method.
     *
     * @param  string $testType
     * @access public
     * @return mixed
     */
    public function prepareRadarDatasetTest(string $testType = 'normal')
    {
        $component = new stdclass();
        $component->option = new stdclass();
        $component->option->dataset = new stdclass();

        switch($testType)
        {
            case 'normal':
                $radarIndicator = array('indicator1', 'indicator2', 'indicator3');
                $seriesData = array('series1', 'series2');
                break;
            case 'empty_indicator':
                $radarIndicator = array();
                $seriesData = array('series1', 'series2');
                break;
            case 'empty_series':
                $radarIndicator = array('indicator1', 'indicator2');
                $seriesData = array();
                break;
            default:
                $radarIndicator = array('indicator1', 'indicator2', 'indicator3');
                $seriesData = array('series1', 'series2');
        }

        $result = $this->objectModel->prepareRadarDataset($component, $radarIndicator, $seriesData);
        if(dao::isError()) return dao::getError();

        $testResult = array();
        $testResult['radarIndicator'] = isset($result->option->dataset->radarIndicator) ? $result->option->dataset->radarIndicator : array();
        $testResult['seriesData'] = isset($result->option->dataset->seriesData) ? $result->option->dataset->seriesData : array();
        $testResult['radarCount'] = isset($result->option->dataset->radarIndicator) ? count($result->option->dataset->radarIndicator) : 0;
        $testResult['seriesCount'] = isset($result->option->dataset->seriesData) ? count($result->option->dataset->seriesData) : 0;
        $testResult['hasStyles'] = isset($result->styles) ? 'yes' : 'no';
        $testResult['result'] = is_object($result) ? 'object' : 'other';

        return $testResult;
    }

    /**
     * Test processMetricFilter method.
     *
     * @param  array  $filterParams
     * @param  string $dateType
     * @access public
     * @return mixed
     */
    public function processMetricFilterTest(array $filterParams, string $dateType)
    {
        $result = $this->objectModel->processMetricFilter($filterParams, $dateType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test formatMetricDateByType method.
     *
     * @param  string $stamp
     * @param  string $dateType
     * @access public
     * @return mixed
     */
    public function formatMetricDateByTypeTest($stamp, $dateType)
    {
        $result = $this->objectModel->formatMetricDateByType($stamp, $dateType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getOptionsFromSql method.
     *
     * @param  string $sql
     * @param  string $keyField
     * @param  string $valueField
     * @access public
     * @return mixed
     */
    public function getOptionsFromSqlTest($sql, $keyField, $valueField)
    {
        $result = $this->objectModel->getOptionsFromSql($sql, $keyField, $valueField);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildGroup method.
     *
     * @param  object $component
     * @access public
     * @return object
     */
    public function buildGroupTest($component)
    {
        $result = $this->objectModel->buildGroup($component);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildTableChart method.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildTableChartTest($component, $chart)
    {
        $result = $this->objectModel->buildTableChart($component, $chart);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getWaterPoloOption method.
     *
     * @param  object $component
     * @param  object $chart
     * @param  array  $filters
     * @access public
     * @return mixed
     */
    public function getWaterPoloOptionTest($component, $chart, $filters = array())
    {
        try {
            // 确保config中有WaterPolo配置
            global $config;
            if(!isset($config->screen->chartConfig['WaterPolo'])) {
                $config->screen->chartConfig['WaterPolo'] = '{"key": "WaterPolo", "package": "Charts"}';
            }
            if(!isset($config->screen->chartOption['WaterPolo'])) {
                $config->screen->chartOption['WaterPolo'] = '{"type":"nomal","series":[{"type":"liquidFill","radius":"90%"}],"backgroundColor":"rgba(0,0,0,0)"}';
            }
            
            $result = $this->objectModel->getWaterPoloOption($component, $chart, $filters);
            if(dao::isError()) return dao::getError();

            // 转换为可测试的格式
            $testResult = array();
            $testResult['hasOption'] = isset($result->option) ? 1 : 0;
            $testResult['hasDataset'] = (isset($result->option) && isset($result->option->dataset)) ? 1 : 0;
            $testResult['datasetValue'] = (isset($result->option) && isset($result->option->dataset)) ? $result->option->dataset : 'null';
            $testResult['hasStyles'] = isset($result->styles) ? 1 : 0;
            $testResult['componentType'] = is_object($result) ? 'object' : gettype($result);
            
            return $testResult;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test getMetricChartOption method.
     *
     * @param  object $metric
     * @param  array  $resultHeader
     * @param  array  $resultData
     * @param  object $component
     * @access public
     * @return mixed
     */
    public function getMetricChartOptionTest($metric, $resultHeader, $resultData, $component = null)
    {
        $result = $this->objectModel->getMetricChartOption($metric, $resultHeader, $resultData, $component);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMetricTableOption method.
     *
     * @param  mixed $metric
     * @param  mixed $resultHeader
     * @param  mixed $resultData
     * @param  mixed $component
     * @access public
     * @return mixed
     */
    public function getMetricTableOptionTest($metric, $resultHeader, $resultData, $component = null)
    {
        $result = $this->objectModel->getMetricTableOption($metric, $resultHeader, $resultData, $component);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMetricCardOption method.
     *
     * @param  int         $metricId
     * @param  array       $resultData
     * @param  object|null $component
     * @access public
     * @return object
     */
    public function getMetricCardOptionTest($metricId, $resultData, $component = null)
    {
        global $tester;
        
        // 从数据库获取metric对象
        $metric = $tester->dao->select('*')->from(TABLE_METRIC)->where('id')->eq($metricId)->fetch();
        if(empty($metric))
        {
            return new stdclass();
        }
        
        $result = $this->objectModel->getMetricCardOption($metric, $resultData, $component);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMetricHeaders method.
     *
     * @param  array  $resultHeader
     * @param  string $dateType
     * @access public
     * @return mixed
     */
    public function getMetricHeadersTest($resultHeader, $dateType)
    {
        $result = $this->objectModel->getMetricHeaders($resultHeader, $dateType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildOrgChart method.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return mixed
     */
    public function buildOrgChartTest($component, $chart)
    {
        $result = $this->objectModel->buildOrgChart($component, $chart);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildFunnelChart method.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return mixed
     */
    public function buildFunnelChartTest($component, $chart)
    {
        $result = $this->objectModel->buildFunnelChart($component, $chart);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test initMetricComponent method.
     *
     * @param  object|null $metric
     * @param  object|null $component
     * @access public
     * @return array
     */
    public function initMetricComponentTest($metric = null, $component = null)
    {
        $result = $this->objectModel->initMetricComponent($metric, $component);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test initChartAndPivotComponent method.
     *
     * @param  object|null $chart
     * @param  string      $type
     * @param  object|null $component
     * @access public
     * @return array
     */
    public function initChartAndPivotComponentTest($chart = null, $type = 'chart', $component = null)
    {
        $result = $this->objectModel->initChartAndPivotComponent($chart, $type, $component);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test initOptionTitle method.
     *
     * @param  object $component
     * @param  string $type
     * @param  string $chartName
     * @access public
     * @return object
     */
    public function initOptionTitleTest($component, $type, $chartName)
    {
        $result = $this->objectModel->initOptionTitle($component, $type, $chartName);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkIFChartInUse method.
     *
     * @param  int    $chartID
     * @param  string $type
     * @access public
     * @return bool
     */
    public function checkIFChartInUseTest($chartID, $type = 'chart')
    {
        $result = $this->objectModel->checkIFChartInUse($chartID, $type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getChartType method.
     *
     * @param  string $type
     * @access public
     * @return string
     */
    public function getChartTypeTest($type)
    {
        $result = $this->objectModel->getChartType($type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildDataset method.
     *
     * @param  int    $chartID
     * @param  string $driver
     * @param  string $sql
     * @access public
     * @return mixed
     */
    public function buildDatasetTest($chartID, $driver, $sql = '')
    {
        $result = $this->objectModel->buildDataset($chartID, $driver, $sql);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDatasetForUsageReport method.
     *
     * @param  int $chartID
     * @access public
     * @return mixed
     */
    public function getDatasetForUsageReportTest($chartID)
    {
        $result = $this->objectModel->getDatasetForUsageReport($chartID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getActiveUserTable method.
     *
     * @param  string $year
     * @param  string $month
     * @param  array  $projectList
     * @access public
     * @return array
     */
    public function getActiveUserTableTest($year, $month, $projectList)
    {
        $result = $this->objectModel->getActiveUserTable($year, $month, $projectList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getActiveProjectCard method.
     *
     * @param  string $year
     * @param  string $month
     * @access public
     * @return array
     */
    public function getActiveProjectCardTest($year, $month)
    {
        $result = $this->objectModel->getActiveProjectCard($year, $month);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getActiveProductCard method.
     *
     * @param  string $year
     * @param  string $month
     * @access public
     * @return array
     */
    public function getActiveProductCardTest($year, $month)
    {
        $result = $this->objectModel->getActiveProductCard($year, $month);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProductTestTable method.
     *
     * @param  string $year
     * @param  string $month
     * @param  array  $productList
     * @access public
     * @return array
     */
    public function getProductTestTableTest($year, $month, $productList)
    {
        $result = $this->objectModel->getProductTestTable($year, $month, $productList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProjectTaskTable method.
     *
     * @param  string $year
     * @param  string $month
     * @param  array  $projectList
     * @access public
     * @return array
     */
    public function getProjectTaskTableTest($year, $month, $projectList)
    {
        $result = $this->objectModel->getProjectTaskTable($year, $month, $projectList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProductStoryTable method.
     *
     * @param  string $year
     * @param  string $month
     * @param  array  $productList
     * @access public
     * @return array
     */
    public function getProductStoryTableTest($year, $month, $productList)
    {
        $result = $this->objectModel->getProductStoryTable($year, $month, $productList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProjectStoryTable method.
     *
     * @param  string $year
     * @param  string $month
     * @param  array  $projectList
     * @access public
     * @return array
     */
    public function getProjectStoryTableTest($year, $month, $projectList)
    {
        $result = $this->objectModel->getProjectStoryTable($year, $month, $projectList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getUsageReportProjects method.
     *
     * @param  string $year
     * @param  string $month
     * @access public
     * @return array
     */
    public function getUsageReportProjectsTest($year, $month)
    {
        $result = $this->objectModel->getUsageReportProjects($year, $month);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getUsageReportProducts method.
     *
     * @param  string $year
     * @param  string $month
     * @access public
     * @return int
     */
    public function getUsageReportProductsTest($year, $month)
    {
        $result = $this->objectModel->getUsageReportProducts($year, $month);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * Test getThumbnail method.
     *
     * @param  array $screens
     * @access public
     * @return mixed
     */
    public function getThumbnailTest($screens)
    {
        $result = $this->objectModel->getThumbnail($screens);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test removeScheme method.
     *
     * @param  array $screens
     * @access public
     * @return array
     */
    public function removeSchemeTest($screens)
    {
        $result = $this->objectModel->removeScheme($screens);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processRadarData method.
     *
     * @param  string $sql
     * @param  object $settings
     * @access public
     * @return mixed
     */
    public function processRadarDataTest($sql, $settings)
    {
        $indicator = array();
        $seriesData = array();

        // 使用反射调用protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processRadarData');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($sql, $settings, &$indicator, &$seriesData));
        if(dao::isError()) return dao::getError();

        return array(
            'result' => $result,
            'resultCount' => count($result),
            'indicator' => $indicator,
            'indicatorCount' => count($indicator),
            'seriesData' => $seriesData,
            'seriesDataCount' => count($seriesData)
        );
    }

    /**
     * Test commonAction method.
     *
     * @param  int  $dimensionID
     * @param  bool $setMenu
     * @access public
     * @return mixed
     */
    public function commonActionTest($dimensionID, $setMenu = true)
    {
        global $tester;

        // 包含model.php和zen.php文件
        include_once dirname(__FILE__, 3) . '/model.php';
        include_once dirname(__FILE__, 3) . '/zen.php';

        // 创建zen对象并调用方法
        $screenZen = new screenZen();

        // 使用反射调用protected方法
        $reflection = new ReflectionClass($screenZen);
        $method = $reflection->getMethod('commonAction');
        $method->setAccessible(true);

        $result = $method->invokeArgs($screenZen, array($dimensionID, $setMenu));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test prepareCardList method.
     *
     * @param  array $screens
     * @access public
     * @return array
     */
    public function prepareCardListTest(array $screens)
    {
        global $tester;

        // 包含model.php和zen.php文件
        include_once dirname(__FILE__, 3) . '/model.php';
        include_once dirname(__FILE__, 3) . '/zen.php';

        // 创建zen对象并调用方法
        $screenZen = new screenZen();

        // 使用反射调用protected方法
        $reflection = new ReflectionClass($screenZen);
        $method = $reflection->getMethod('prepareCardList');
        $method->setAccessible(true);

        $result = $method->invokeArgs($screenZen, array($screens));
        if(dao::isError()) return dao::getError();

        return $result;
    }

}
