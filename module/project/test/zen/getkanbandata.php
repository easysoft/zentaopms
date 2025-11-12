#!/usr/bin/env php
<?php

/**

title=测试 projectZen::getKanbanData();
timeout=0
cid=0

- 步骤1:正常情况下获取看板数据 @2
- 步骤2:验证第一个区域的key为my第0条的key属性 @my
- 步骤3:验证第二个区域的key为other第1条的key属性 @other
- 步骤4:验证第一个区域items不为空 @1
- 步骤5:验证第二个区域的items包含数据 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zenData('project')->loadYaml('execution')->gen(100)->fixPath();
zenData('user')->gen(100);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$projectTest = new projectZenTest();

// 5. 测试步骤
r(count($projectTest->getKanbanDataTest())) && p() && e('2'); // 步骤1:正常情况下获取看板数据
r($projectTest->getKanbanDataTest()) && p('0:key') && e('my'); // 步骤2:验证第一个区域的key为my
r($projectTest->getKanbanDataTest()) && p('1:key') && e('other'); // 步骤3:验证第二个区域的key为other
r(count($projectTest->getKanbanDataTest()[0]['items'])) && p() && e('1'); // 步骤4:验证第一个区域items不为空
r(count($projectTest->getKanbanDataTest()[1]['items'])) && p() && e('1'); // 步骤5:验证第二个区域的items包含数据