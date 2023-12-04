<?php
class screenTest
{

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
    public function getByIDTest(int $screenID, int $year = 0, int $dept = 0, string $account = ''): object|bool
    {
        return $this->objectModel->getByID($screenID, $year, $dept, $account);
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
        $sqlFile = $appPath . 'data/screen.sql';
        $tester->dbh->exec(file_get_contents($sqlFile));
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
        return $this->objectModel->getChartFilters($chart);
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
        $this->objectModel->genComponentData($chart, $component, $type, $filter, true);
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
}
