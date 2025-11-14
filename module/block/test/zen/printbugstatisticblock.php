#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printBugStatisticBlock();
timeout=0
cid=15251

- 执行blockTest模块的printBugStatisticBlockTest方法，参数是$normalBlock 属性totalBugs @10
- 执行blockTest模块的printBugStatisticBlockTest方法，参数是$shadowBlock, $shadowParams 属性totalBugs @15
- 执行blockTest模块的printBugStatisticBlockTest方法，参数是$emptyBlock 属性totalBugs @10
- 执行blockTest模块的printBugStatisticBlockTest方法，参数是$stringZeroCountBlock 属性totalBugs @0
- 执行blockTest模块的printBugStatisticBlockTest方法，参数是$statusBlock 属性totalBugs @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 准备基础测试数据
$table = zenData('product');
$table->id->range('1-8');
$table->name->range('正常产品1,正常产品2,正常产品3,正常产品4,正常产品5,影子产品1,影子产品2,已关闭产品1');
$table->code->range('product1,product2,product3,product4,product5,shadow1,shadow2,closed1');
$table->status->range('normal{7},closed{1}');
$table->shadow->range('0{5},1{2},0{1}');
$table->deleted->range('0{8}');
$table->gen(8);

su('admin');

$blockTest = new blockTest();

// 测试场景1：正常block对象，传入普通产品ID
$normalBlock = new stdclass();
$normalBlock->params = new stdclass();
$normalBlock->params->type = '';
$normalBlock->params->count = 10;

// 测试场景2：影子产品测试
$shadowBlock = new stdclass();
$shadowBlock->params = new stdclass();
$shadowBlock->params->type = '';
$shadowBlock->params->count = 10;
$shadowParams = array('active' => 6);

// 测试场景3：空block对象
$emptyBlock = new stdclass();

// 测试场景4：count为字符串'0'的block对象
$stringZeroCountBlock = new stdclass();
$stringZeroCountBlock->params = new stdclass();
$stringZeroCountBlock->params->type = '';
$stringZeroCountBlock->params->count = '0';

// 测试场景5：按状态过滤的block对象
$statusBlock = new stdclass();
$statusBlock->params = new stdclass();
$statusBlock->params->type = 'normal';
$statusBlock->params->count = 10;

// 步骤1：正常情况测试 - 验证普通产品的Bug总数
r($blockTest->printBugStatisticBlockTest($normalBlock)) && p('totalBugs') && e('10');
// 步骤2：影子产品测试 - 验证影子产品的Bug总数
r($blockTest->printBugStatisticBlockTest($shadowBlock, $shadowParams)) && p('totalBugs') && e('15');
// 步骤3：空对象测试 - 验证默认选择第一个产品
r($blockTest->printBugStatisticBlockTest($emptyBlock)) && p('totalBugs') && e('10');
// 步骤4：count为字符串'0'测试 - 验证count为'0'时Bug总数为0
r($blockTest->printBugStatisticBlockTest($stringZeroCountBlock)) && p('totalBugs') && e('0');
// 步骤5：按状态过滤测试 - 验证按normal状态过滤后的Bug总数
r($blockTest->printBugStatisticBlockTest($statusBlock)) && p('totalBugs') && e('10');