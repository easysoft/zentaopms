#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::getImportHeaderAndColumns();
timeout=0
cid=15549

- 执行caselibTest模块的getImportHeaderAndColumnsTest方法，参数是$normalCsvFile, $testFields, 'header_count'  @4
- 执行caselibTest模块的getImportHeaderAndColumnsTest方法，参数是$emptyCsvFile, $testFields, 'is_empty'  @1
- 执行caselibTest模块的getImportHeaderAndColumnsTest方法，参数是$headerOnlyCsvFile, $testFields, 'header_first'  @标题
- 执行caselibTest模块的getImportHeaderAndColumnsTest方法，参数是$normalCsvFile, $testFields, 'columns_count'  @4
- 执行caselibTest模块的getImportHeaderAndColumnsTest方法，参数是$normalCsvFile, $noMatchFields, 'columns_count'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

zenData('testcase')->gen(0);

su('admin');

$caselibTest = new caselibTest();

$testDataDir = dirname(__FILE__) . '/data/';
if(!is_dir($testDataDir)) mkdir($testDataDir, 0755, true);

$normalCsvFile = $testDataDir . 'normal_import.csv';
$emptyCsvFile = $testDataDir . 'empty_import.csv';
$headerOnlyCsvFile = $testDataDir . 'header_only_import.csv';

file_put_contents($normalCsvFile, "标题,类型,优先级,所属模块\n测试用例1,功能测试,高,模块1\n测试用例2,性能测试,中,模块2");
file_put_contents($emptyCsvFile, "");
file_put_contents($headerOnlyCsvFile, "标题,类型,优先级,所属模块");

$testFields = array(
    '标题' => 'title',
    '类型' => 'type',
    '优先级' => 'pri',
    '所属模块' => 'module',
    '步骤' => 'stepDesc'
);

$noMatchFields = array(
    '不存在字段1' => 'field1',
    '不存在字段2' => 'field2'
);

r($caselibTest->getImportHeaderAndColumnsTest($normalCsvFile, $testFields, 'header_count')) && p() && e('4');
r($caselibTest->getImportHeaderAndColumnsTest($emptyCsvFile, $testFields, 'is_empty')) && p() && e('1');
r($caselibTest->getImportHeaderAndColumnsTest($headerOnlyCsvFile, $testFields, 'header_first')) && p() && e('标题');
r($caselibTest->getImportHeaderAndColumnsTest($normalCsvFile, $testFields, 'columns_count')) && p() && e('4');
r($caselibTest->getImportHeaderAndColumnsTest($normalCsvFile, $noMatchFields, 'columns_count')) && p() && e('0');

unlink($normalCsvFile);
unlink($emptyCsvFile);
unlink($headerOnlyCsvFile);
if(is_dir($testDataDir) && count(scandir($testDataDir)) <= 2) rmdir($testDataDir);