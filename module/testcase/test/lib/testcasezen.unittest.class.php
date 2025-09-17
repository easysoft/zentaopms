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
}
