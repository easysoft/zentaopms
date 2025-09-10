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
}
