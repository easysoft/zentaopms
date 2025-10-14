#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printBugStatisticBlock();
timeout=0
cid=0

- 执行blockTest模块的printBugStatisticBlockTest方法，参数是$normalBlock
 - 属性totalBugs @10
 - 属性success @1
- 执行blockTest模块的printBugStatisticBlockTest方法，参数是$emptyCountBlock
 - 属性totalBugs @0
 - 属性closedBugs @0
 - 属性unresovledBugs @0
- 执行blockTest模块的printBugStatisticBlockTest方法，参数是$statusBlock
 - 属性totalBugs @10
 - 属性success @1
- 执行blockTest模块的printBugStatisticBlockTest方法，参数是$countBlock 属性totalBugs @10
- 执行blockTest模块的printBugStatisticBlockTest方法，参数是$normalBlock, array
 - 属性totalBugs @15
 - 属性resolvedRate @60
- 执行blockTest模块的printBugStatisticBlockTest方法，参数是$normalBlock, array
 - 属性totalBugs @0
 - 属性closedBugs @0
- 执行blockTest模块的printBugStatisticBlockTest方法，参数是null
 - 属性totalBugs @10
 - 属性success @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 准备更完整的产品数据
$productTable = zenData('product');
$productTable->id->range('1-20');
$productTable->name->range('正常产品1,正常产品2,正常产品3,正常产品4,正常产品5,影子产品1,影子产品2,已关闭产品1,已关闭产品2,开发中产品1{10}');
$productTable->code->range('normal1,normal2,normal3,normal4,normal5,shadow1,shadow2,closed1,closed2,dev1{10}');
$productTable->status->range('normal{5},normal{2},closed{3},developing{10}');
$productTable->shadow->range('0{5},1{2},0{13}');
$productTable->deleted->range('0{18},1{2}');
$productTable->order->range('1-20');
$productTable->gen(20);

// 准备项目产品关联数据
$projectProductTable = zenData('projectproduct');
$projectProductTable->project->range('1-3');
$projectProductTable->product->range('6,7,8');
$projectProductTable->gen(3);

// 准备测试用户
su('admin');

$blockTest = new blockTest();

// 创建各种测试场景的block对象
$normalBlock = new stdClass();
$normalBlock->params = new stdClass();
$normalBlock->params->type = '';
$normalBlock->params->count = '';

$emptyCountBlock = new stdClass();
$emptyCountBlock->params = new stdClass();
$emptyCountBlock->params->type = '';
$emptyCountBlock->params->count = '0';

$statusBlock = new stdClass();
$statusBlock->params = new stdClass();
$statusBlock->params->type = 'normal';
$statusBlock->params->count = '';

$countBlock = new stdClass();
$countBlock->params = new stdClass();
$countBlock->params->type = '';
$countBlock->params->count = '3';

$closedStatusBlock = new stdClass();
$closedStatusBlock->params = new stdClass();
$closedStatusBlock->params->type = 'closed';
$closedStatusBlock->params->count = '';

// 测试步骤1：正常block参数获取Bug统计信息
r($blockTest->printBugStatisticBlockTest($normalBlock)) && p('totalBugs,success') && e('10,1');

// 测试步骤2：空count参数时的Bug统计处理
r($blockTest->printBugStatisticBlockTest($emptyCountBlock)) && p('totalBugs,closedBugs,unresovledBugs') && e('0,0,0');

// 测试步骤3：指定产品状态的Bug统计筛选
r($blockTest->printBugStatisticBlockTest($statusBlock)) && p('totalBugs,success') && e('10,1');

// 测试步骤4：测试数量限制参数的Bug统计功能
r($blockTest->printBugStatisticBlockTest($countBlock)) && p('totalBugs') && e('10');

// 测试步骤5：影子产品的Bug统计数据获取
r($blockTest->printBugStatisticBlockTest($normalBlock, array('active' => '6'))) && p('totalBugs,resolvedRate') && e('15,60');

// 测试步骤6：测试无效产品ID的异常处理
r($blockTest->printBugStatisticBlockTest($normalBlock, array('active' => '999'))) && p('totalBugs,closedBugs') && e('0,0');

// 测试步骤7：测试null block参数的容错处理
r($blockTest->printBugStatisticBlockTest(null)) && p('totalBugs,success') && e('10,1');

// 测试步骤8：测试月份数据统计数组长度验证
r($blockTest->printBugStatisticBlockTest($normalBlock)) && p('months') && c('6');