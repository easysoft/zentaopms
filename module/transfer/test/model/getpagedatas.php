#!/usr/bin/env php
<?php

/**

title=测试 transferModel::getPageDatas();
timeout=0
cid=19316

- 执行transferTest模块的getPageDatasTest方法，参数是$testDatas, 1 属性allCount @25
- 执行transferTest模块的getPageDatasTest方法，参数是$smallDatas, 1 属性allCount @5
- 执行transferTest模块的getPageDatasTest方法，参数是$testDatas, 1 属性allCount @25
- 执行transferTest模块的getPageDatasTest方法，参数是$testDatas, 1 属性allPager @1
- 执行transferTest模块的getPageDatasTest方法，参数是$testDatas, 1 属性isEndPage @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$transferTest = new transferModelTest();

$testDatas = array();
for($i = 1; $i <= 25; $i++)
{
    $testDatas[$i] = (object)array('id' => $i, 'title' => 'Test Data ' . $i);
}

global $tester;
$transfer = $tester->loadModel('transfer');

// 步骤1：maxImport为10时的分页处理
$transfer->maxImport = 10;
$tester->config->file->maxImport = 50;
r($transferTest->getPageDatasTest($testDatas, 1)) && p('allCount') && e('25');

// 步骤2：小数据量处理
$transfer->maxImport = 0;
$tester->config->file->maxImport = 50;
$smallDatas = array_slice($testDatas, 0, 5, true);
r($transferTest->getPageDatasTest($smallDatas, 1)) && p('allCount') && e('5');

// 步骤3：maxImport为0时的处理
$transfer->maxImport = 0;
$tester->config->file->maxImport = 10;
r($transferTest->getPageDatasTest($testDatas, 1)) && p('allCount') && e('25');

// 步骤4：验证总页数计算，当maxImport为0时allPager为1
$transfer->maxImport = 0;
$tester->config->file->maxImport = 50;
r($transferTest->getPageDatasTest($testDatas, 1)) && p('allPager') && e('1');

// 步骤5：验证是否为最后一页
$transfer->maxImport = 25;
$tester->config->file->maxImport = 50;
r($transferTest->getPageDatasTest($testDatas, 1)) && p('isEndPage') && e('1');