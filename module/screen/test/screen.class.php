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
    public function getAllComponent(array $filters = array())
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
            $componentList = [];
            foreach($screenList as $screen)
            {
                if(!in_array($screen->id, array(5, 6, 8)))
                {
                    $componentList = array_merge($componentList, json_decode($screen->scheme));
                }
                else
                {
                    $scheme = json_decode($screen->scheme);
                    if($scheme) $componentList = array_merge($componentList, $scheme->componentList);
                }
            }
            $this->componentList = $componentList;
        }

        return array_filter($componentList, function($component)use($filters){
            foreach($filters as $field => $value)
            {
                if(isset($component->$field) && $component->$field == $value) return true;
            }

            return false;
        });
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
}
