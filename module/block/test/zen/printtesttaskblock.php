#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printTesttaskBlock();
timeout=0
cid=15307

- 步骤1:测试正常情况下传入type参数为wait和count参数为10
 - 属性type @wait
 - 属性count @10
- 步骤2:测试type参数包含特殊字符时验证失败属性type @invalid-type
- 步骤3:测试type参数为all时查询所有测试单属性type @all
- 步骤4:测试type参数为doing时查询进行中的测试单
 - 属性type @doing
 - 属性count @5
- 步骤5:测试type参数为done时查询已完成的测试单属性type @done
- 步骤6:测试count为0时的处理属性count @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$blockTest = new blockZenTest();

// 4. 准备测试数据
$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->type = 'wait';
$block1->params->count = 10;

$block2 = new stdclass();
$block2->params = new stdclass();
$block2->params->type = 'invalid-type';
$block2->params->count = 10;

$block3 = new stdclass();
$block3->params = new stdclass();
$block3->params->type = 'all';
$block3->params->count = 10;

$block4 = new stdclass();
$block4->params = new stdclass();
$block4->params->type = 'doing';
$block4->params->count = 5;

$block5 = new stdclass();
$block5->params = new stdclass();
$block5->params->type = 'done';
$block5->params->count = 10;

$block6 = new stdclass();
$block6->params = new stdclass();
$block6->params->type = 'wait';
$block6->params->count = 0;

// 5. 强制要求:必须包含至少5个测试步骤
r($blockTest->printTesttaskBlockTest($block1)) && p('type,count') && e('wait,10'); // 步骤1:测试正常情况下传入type参数为wait和count参数为10
r($blockTest->printTesttaskBlockTest($block2)) && p('type') && e('invalid-type'); // 步骤2:测试type参数包含特殊字符时验证失败
r($blockTest->printTesttaskBlockTest($block3)) && p('type') && e('all'); // 步骤3:测试type参数为all时查询所有测试单
r($blockTest->printTesttaskBlockTest($block4)) && p('type,count') && e('doing,5'); // 步骤4:测试type参数为doing时查询进行中的测试单
r($blockTest->printTesttaskBlockTest($block5)) && p('type') && e('done'); // 步骤5:测试type参数为done时查询已完成的测试单
r($blockTest->printTesttaskBlockTest($block6)) && p('count') && e('0'); // 步骤6:测试count为0时的处理