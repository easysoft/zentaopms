#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumProductBlock();
timeout=0
cid=0

- 步骤1：正常情况 @1
- 步骤2：设置count参数 @1
- 步骤3：默认参数 @1
- 步骤4：无效参数 @1
- 步骤5：边界值测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 简化测试，不生成复杂数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($blockTest->printScrumProductBlockTest(null)) && p() && e('1'); // 步骤1：正常情况

$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->count = 10;
r($blockTest->printScrumProductBlockTest($block1)) && p() && e('1'); // 步骤2：设置count参数

$block2 = new stdclass();
$block2->params = new stdclass();
r($blockTest->printScrumProductBlockTest($block2)) && p() && e('1'); // 步骤3：默认参数

$block3 = new stdclass();
$block3->params = (object)array('invalid' => 'test');
r($blockTest->printScrumProductBlockTest($block3)) && p() && e('1'); // 步骤4：无效参数

$block4 = new stdclass();
$block4->params = new stdclass();
$block4->params->count = 0;
r($blockTest->printScrumProductBlockTest($block4)) && p() && e('1'); // 步骤5：边界值测试