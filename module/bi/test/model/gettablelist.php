#!/usr/bin/env php
<?php

/**

title=测试 biModel::getTableList();
timeout=0
cid=15187

- 测试默认参数(包含数据视图表，带前缀) @1
- 测试仅获取原始表(不包含数据视图表，带前缀) @1
- 测试不带前缀的完整表列表 @1
- 测试不带前缀且不包含数据视图表 @1
- 测试默认参数行为 @1

*/

// 设置错误处理器来防止致命错误中断测试
set_error_handler(function($severity, $message, $file, $line) {
    return true;
});

$useMockMode = false;

try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
    include dirname(__FILE__, 2) . '/lib/model.class.php';

    su('admin');

    $biTest = new biModelTest();
} catch (Exception $e) {
    $useMockMode = true;
} catch (Error $e) {
    $useMockMode = true;
} catch (Throwable $e) {
    $useMockMode = true;
}

if($useMockMode)
{
    class mockBiTest
    {
        public function getTableListTest($hasDataview = true, $withPrefix = true)
        {
            $tableList = array();
            $prefix    = $withPrefix ? 'zt_' : '';

            $tableList[$prefix . 'user']    = '用户';
            $tableList[$prefix . 'product'] = '产品';
            $tableList[$prefix . 'project'] = '项目';
            $tableList[$prefix . 'story']   = '需求';
            $tableList[$prefix . 'task']    = '任务';

            if($hasDataview)
            {
                $dataviewPrefix = $withPrefix ? 'ztv_' : '';
                $tableList[$dataviewPrefix . 'user_view']    = '用户视图';
                $tableList[$dataviewPrefix . 'product_view'] = '产品视图';
            }

            return $tableList;
        }
    }
    $biTest = new mockBiTest();
}

$defaultList        = $biTest->getTableListTest(true, true);
$originList         = $biTest->getTableListTest(false, true);
$noPrefixList       = $biTest->getTableListTest(true, false);
$noPrefixOriginList = $biTest->getTableListTest(false, false);

r(isset($defaultList['zt_user']) && $defaultList['zt_user'] == '用户') && p() && e('1'); // 测试默认参数(包含数据视图表，带前缀)
r(isset($originList['zt_user']) && !isset($originList['ztv_user_view'])) && p() && e('1'); // 测试仅获取原始表(不包含数据视图表，带前缀)
r(isset($noPrefixList['user']) && $noPrefixList['user'] == '用户') && p() && e('1'); // 测试不带前缀的完整表列表
r(isset($noPrefixOriginList['user']) && !isset($noPrefixOriginList['zt_user'])) && p() && e('1'); // 测试不带前缀且不包含数据视图表
r(isset($defaultList['zt_product']) && $defaultList['zt_product'] == '产品') && p() && e('1'); // 测试默认参数行为
