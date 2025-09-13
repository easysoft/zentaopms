#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printTeamAchievementBlock();
timeout=0
cid=0

- 步骤1：正常情况属性finishedTasks @1
- 步骤2：没有数据属性finishedTasks @0
- 步骤3：只有今日数据
 - 属性finishedTasks @1
 - 属性yesterdayTasks @0
- 步骤4：只有昨日数据
 - 属性finishedTasks @0
 - 属性yesterdayTasks @1
- 步骤5：跨月数据属性finishedTasks @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 不需要准备额外的数据库数据，因为测试方法内部会模拟数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printTeamAchievementBlockTest()) && p('finishedTasks') && e('1'); // 步骤1：正常情况
r($blockTest->printTeamAchievementBlockTest('empty')) && p('finishedTasks') && e('0'); // 步骤2：没有数据
r($blockTest->printTeamAchievementBlockTest('today_only')) && p('finishedTasks,yesterdayTasks') && e('1,0'); // 步骤3：只有今日数据
r($blockTest->printTeamAchievementBlockTest('yesterday_only')) && p('finishedTasks,yesterdayTasks') && e('0,1'); // 步骤4：只有昨日数据
r($blockTest->printTeamAchievementBlockTest('cross_month')) && p('finishedTasks') && e('1'); // 步骤5：跨月数据