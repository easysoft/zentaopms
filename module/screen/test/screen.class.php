<?php
class screenTest
{
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

    public function getAllComponent()
    {
        global $tester;
        $sql = "SELECT * FROM `zt_screen`";
        $screenList = $tester->dbh->query($sql)->fetchAll();
        $componentList = [];
        foreach($screenList as $screen)
        {
            if(!in_array($screen->id, array(5, 6, 8)))
            {
                $componentList = array_merge($componentList, json_encode($screen->scheme));
            }
            else
            {
                $componentList = array_merge($componentList, json_encode($screen->scheme->componentList));
            }
        }

        return $componentList;
    }

    public function completeComponent($component)
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
}
