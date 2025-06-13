<?php
class screenTest
{

    private $objectModel;
    public $componentList = array();

    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('screen');
         $this->initScreen();
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
    public function getByIDTest(int $screenID, int $year = 0, int $month = 0, int $dept = 0, string $account = ''): object|bool
    {
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
                if(!in_array($screen->id, array(5, 6, 8)))
                {

                    if(!$bultion) $componentList_ = json_decode($screen->scheme);
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
    public function buildComponentListTest(array|object $componentList): array
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
    public function setValueByPathTest(object &$option, string $path, mixed $value): void
    {
        $this->objectModel->setValueByPath($option, $path, $value);
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
}
