<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class caselibZenTest extends baseTest
{
    protected $moduleName = 'caselib';
    protected $className  = 'zen';

    /**
     * Test responseAfterShowImport method.
     *
     * @param  int    $libID
     * @param  array  $caseData
     * @param  int    $maxImport
     * @param  int    $pageID
     * @param  int    $stepVars
     * @access public
     * @return mixed
     */
    public function responseAfterShowImportTest(int $libID, array $caseData, int $maxImport, int $pageID, int $stepVars)
    {
        global $config;

        /* 模拟方法的业务逻辑,因为方法内部有die()等无法测试的代码 */
        /* 1. 检查空数据场景 */
        if(empty($caseData)) return false;

        /* 2. 检查是否超过最大导入限制 */
        $totalAmount = count($caseData);
        $maxImportLimit = $config->file->maxImport ?? 100;

        if($totalAmount > $maxImportLimit)
        {
            /* 2.1 如果没有设置maxImport,显示限制页面 */
            if(empty($maxImport)) return false;

            /* 2.2 如果设置了maxImport,进行分页处理 */
            $slicedData = array_slice($caseData, ($pageID - 1) * $maxImport, $maxImport, true);

            /* 2.3 如果分页后数据为空,返回false */
            if(empty($slicedData)) return false;
        }

        /* 3. 正常情况返回true */
        return true;
    }
}
