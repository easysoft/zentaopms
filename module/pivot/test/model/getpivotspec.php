#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getPivotSpec();
timeout=0
cid=17394

- 执行pivotTest模块的getPivotSpecTest方法，参数是1000, '1' 属性id @1000
- 执行pivotTest模块的getPivotSpecTest方法，参数是999999, '1'  @0
- 执行pivotTest模块的getPivotSpecTest方法，参数是1001, 'nonexistent' 属性id @1001
- 执行pivotTest模块的getPivotSpecTest方法，参数是1002, '1', true 属性id @1002
- 执行pivotTest模块的getPivotSpecTest方法，参数是1003, '1', false, false 属性id @1003
- 执行pivotTest模块的getPivotSpecTest方法，参数是1004, '1' 属性id @1004
- 执行pivotTest模块的getPivotSpecTest方法，参数是1005, '1' 属性id @1005

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 初始化pivot测试数据
global $tester, $app;
$appPath = $app->getAppRoot();
$sqlFile = $appPath . 'test/data/pivot.sql';
$tester->dbh->exec(file_get_contents($sqlFile));

su('admin');

$pivotTest = new pivotModelTest();

r($pivotTest->getPivotSpecTest(1000, '1')) && p('id') && e('1000');
r($pivotTest->getPivotSpecTest(999999, '1')) && p() && e('0');
r($pivotTest->getPivotSpecTest(1001, 'nonexistent')) && p('id') && e('1001');
r($pivotTest->getPivotSpecTest(1002, '1', true)) && p('id') && e('1002');
r($pivotTest->getPivotSpecTest(1003, '1', false, false)) && p('id') && e('1003');
r($pivotTest->getPivotSpecTest(1004, '1')) && p('id') && e('1004');
r($pivotTest->getPivotSpecTest(1005, '1')) && p('id') && e('1005');