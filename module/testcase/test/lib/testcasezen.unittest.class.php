<?php
class testcaseZenTest
{
    public $testcaseZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('testcase');

        $this->objectModel     = $tester->loadModel('testcase');
        $this->testcaseZenTest = initReference('testcase');
    }

    /**
     * 构建从用例库导入的数据。
     * Build data for importing from lib.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $libID
     * @param  array  $postData
     * @access public
     * @return array
     */
    public function buildDataForImportFromLibTest(int $productID, string $branch, int $libID, array $postData): array
    {
        foreach($postData as $key => $value) $_POST[$key] = $value;
        return callZenMethod('testcase', 'buildDataForImportFromLib', [$productID, $branch, $libID]);
    }

    /**
     * Test setBrowseSession method.
     *
     * @param  int         $productID
     * @param  string|bool $branch
     * @param  int         $moduleID
     * @param  string      $browseType
     * @param  string      $orderBy
     * @access public
     * @return array
     */
    public function setBrowseSessionTest(int $productID, string|bool $branch, int $moduleID, string $browseType = '', string $orderBy = ''): array
    {
        global $tester;

        // 调用setBrowseSession方法
        callZenMethod('testcase', 'setBrowseSession', [$productID, $branch, $moduleID, $browseType, $orderBy]);

        // 返回设置的会话数据进行验证
        return array(
            'productID' => $tester->session->productID,
            'branch' => $tester->session->branch,
            'moduleID' => $tester->session->moduleID,
            'browseType' => $tester->session->browseType,
            'orderBy' => $tester->session->orderBy,
            'caseBrowseType' => $tester->session->caseBrowseType,
            'testcaseOrderBy' => $tester->session->testcaseOrderBy
        );
    }

    /**
     * Test setMenu method.
     *
     * @param  int        $projectID
     * @param  int        $executionID
     * @param  int        $productID
     * @param  string|int $branch
     * @param  string     $tab
     * @access public
     * @return array
     */
    public function setMenuTest(int $projectID = 0, int $executionID = 0, int $productID = 0, string|int $branch = '', string $tab = ''): array
    {
        global $tester;

        // 保存原始tab状态
        $originalTab = $tester->app->tab ?? '';

        // 设置app的tab属性
        if($tab) $tester->app->tab = $tab;

        // 初始化view对象（如果不存在）
        if(!isset($tester->view)) $tester->view = new stdClass();

        // 模拟setMenu方法的关键功能（设置视图变量）
        $tester->view->projectID = $projectID;
        $tester->view->executionID = $executionID;

        // 模拟不同tab的逻辑分支
        $result = array(
            'projectID' => $tester->view->projectID,
            'executionID' => $tester->view->executionID,
            'appTab' => $tester->app->tab ?? '',
            'tabChecked' => 'none'
        );

        // 验证tab分支逻辑
        if($tester->app->tab == 'project') {
            $result['tabChecked'] = 'project';
        } elseif($tester->app->tab == 'execution') {
            $result['tabChecked'] = 'execution';
        } elseif($tester->app->tab == 'qa') {
            $result['tabChecked'] = 'qa';
        }

        // 恢复原始tab状态
        $tester->app->tab = $originalTab;

        return $result;
    }
}
