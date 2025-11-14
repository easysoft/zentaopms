#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProductOverviewBlock();
timeout=0
cid=15271

- 步骤1：短区块宽度为1
 - 属性success @1
 - 属性blockWidth @1
- 步骤2：长区块宽度为3
 - 属性success @1
 - 属性blockWidth @3
- 步骤3：空宽度处理属性success @1
- 步骤4：带年份参数
 - 属性success @1
 - 属性blockWidth @3
- 步骤5：其他宽度值
 - 属性success @1
 - 属性blockWidth @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备
$table = zenData('block');
$table->id->range('1-10');
$table->module->range('product');
$table->title->range('产品总览区块{10}');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$blockTest = new blockTest();

// 5. 创建测试数据对象
$block1 = new stdclass();
$block1->width = 1;

$block2 = new stdclass();
$block2->width = 3;

$block3 = new stdclass();
$block3->width = null;

$block4 = new stdclass();
$block4->width = 3;
$params4 = array('year' => 2023);

$block5 = new stdclass();
$block5->width = 2;

// 6. 必须包含至少5个测试步骤
r($blockTest->printProductOverviewBlockTest($block1)) && p('success,blockWidth') && e('1,1'); // 步骤1：短区块宽度为1
r($blockTest->printProductOverviewBlockTest($block2)) && p('success,blockWidth') && e('1,3'); // 步骤2：长区块宽度为3
r($blockTest->printProductOverviewBlockTest($block3)) && p('success') && e('1'); // 步骤3：空宽度处理
r($blockTest->printProductOverviewBlockTest($block4, $params4)) && p('success,blockWidth') && e('1,3'); // 步骤4：带年份参数
r($blockTest->printProductOverviewBlockTest($block5)) && p('success,blockWidth') && e('1,2'); // 步骤5：其他宽度值