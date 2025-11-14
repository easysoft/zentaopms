#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printReleaseStatisticBlock();
timeout=0
cid=15283

- 执行blockTest模块的printReleaseStatisticBlockTest方法，参数是$normalBlock 属性releaseDataCount @6
- 执行blockTest模块的printReleaseStatisticBlockTest方法，参数是$emptyBlock 属性releasesCount @5
- 执行blockTest模块的printReleaseStatisticBlockTest方法，参数是$partialBlock 属性releaseDataCount @6
- 执行blockTest模块的printReleaseStatisticBlockTest方法，参数是$blockWithoutParams 属性releasesCount @5
- 执行blockTest模块的printReleaseStatisticBlockTest方法，参数是$normalBlock  @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 准备基础测试数据
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->code->range('product1,product2,product3,product4,product5');
$table->status->range('normal{5}');
$table->deleted->range('0{5}');
$table->gen(5);

su('admin');

$blockTest = new blockTest();

$normalBlock = new stdclass();
$normalBlock->params = new stdclass();
$normalBlock->params->count = 10;

$emptyBlock = new stdclass();

$partialBlock = new stdclass();
$partialBlock->params = new stdclass();

$blockWithoutParams = new stdclass();

// 步骤1：正常情况测试 - 返回结果对象类型验证
r($blockTest->printReleaseStatisticBlockTest($normalBlock)) && p('releaseDataCount') && e('6');

// 步骤2：空对象测试 - 验证异常处理能力
r($blockTest->printReleaseStatisticBlockTest($emptyBlock)) && p('releasesCount') && e('5');

// 步骤3：部分属性缺失测试 - 验证默认值处理
r($blockTest->printReleaseStatisticBlockTest($partialBlock)) && p('releaseDataCount') && e('6');

// 步骤4：无params属性测试 - 验证鲁棒性
r($blockTest->printReleaseStatisticBlockTest($blockWithoutParams)) && p('releasesCount') && e('5');

// 步骤5：数据结构完整性测试 - 验证返回数据是否为数组结构
r($blockTest->printReleaseStatisticBlockTest($normalBlock)) && p() && e('~~');