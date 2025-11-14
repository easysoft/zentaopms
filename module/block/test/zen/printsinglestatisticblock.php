#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleStatisticBlock();
timeout=0
cid=15301

- 执行blockTest模块的printSingleStatisticBlockTest方法，参数是$emptyBlock 属性productID @1
- 执行blockTest模块的printSingleStatisticBlockTest方法，参数是$normalBlock 属性totalStories @0
- 执行blockTest模块的printSingleStatisticBlockTest方法，参数是$typeBlock 属性closedStories @0
- 执行blockTest模块的printSingleStatisticBlockTest方法，参数是$zeroCountBlock 属性unclosedStories @0
- 执行blockTest模块的printSingleStatisticBlockTest方法，参数是$largeCountBlock 属性monthFinishCount @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 准备产品数据
zendata('product')->loadYaml('product', false, 2)->gen(10);

// 准备度量数据
zendata('metriclib')->loadYaml('metriclib', false, 2)->gen(100);

// 准备计划数据
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);

// 准备执行数据
zendata('project')->loadYaml('execution', false, 2)->gen(20);

// 准备项目产品关联数据
zendata('projectproduct')->loadYaml('projectproduct', false, 2)->gen(20);

// 准备发布数据
zendata('release')->loadYaml('release', false, 2)->gen(10);

// 设置session中的产品ID
su('admin');
$_SESSION['product'] = 1;

$blockTest = new blockZenTest();

// 测试场景1：空的block对象，使用默认参数
$emptyBlock = new stdclass();
$emptyBlock->params = new stdclass();

// 测试场景2：正常block对象，type为空字符串，count为10
$normalBlock = new stdclass();
$normalBlock->params = new stdclass();
$normalBlock->params->type = '';
$normalBlock->params->count = 10;

// 测试场景3：block对象设置type为normal，count为5
$typeBlock = new stdclass();
$typeBlock->params = new stdclass();
$typeBlock->params->type = 'normal';
$typeBlock->params->count = 5;

// 测试场景4：block对象设置count为0
$zeroCountBlock = new stdclass();
$zeroCountBlock->params = new stdclass();
$zeroCountBlock->params->type = '';
$zeroCountBlock->params->count = 0;

// 测试场景5：block对象设置较大count值
$largeCountBlock = new stdclass();
$largeCountBlock->params = new stdclass();
$largeCountBlock->params->type = '';
$largeCountBlock->params->count = 100;

r($blockTest->printSingleStatisticBlockTest($emptyBlock)) && p('productID') && e('1');
r($blockTest->printSingleStatisticBlockTest($normalBlock)) && p('totalStories') && e('0');
r($blockTest->printSingleStatisticBlockTest($typeBlock)) && p('closedStories') && e('0');
r($blockTest->printSingleStatisticBlockTest($zeroCountBlock)) && p('unclosedStories') && e('0');
r($blockTest->printSingleStatisticBlockTest($largeCountBlock)) && p('monthFinishCount') && e('6');