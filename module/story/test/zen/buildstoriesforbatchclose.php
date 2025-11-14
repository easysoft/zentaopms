#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildStoriesForBatchClose();
timeout=0
cid=18666

- 步骤1：正常情况
 - 第1条的status属性 @closed
 - 第1条的assignedTo属性 @closed
- 步骤2：验证返回数组数量属性count @3
- 步骤3：postponed关闭原因第3条的closedReason属性 @postponed
- 步骤4：postponed原因清空plan第3条的plan属性 @~~
- 步骤5：stage字段设置为closed第1条的stage属性 @closed

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. 不使用zendata，直接使用模拟数据进行测试

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $storyTest->buildStoriesForBatchCloseTest();
$result2 = $storyTest->buildStoriesForBatchCloseTest();
$result3 = $storyTest->buildStoriesForBatchCloseTest();
$result4 = $storyTest->buildStoriesForBatchCloseTest();
$result5 = $storyTest->buildStoriesForBatchCloseTest();

r($result1) && p('1:status,assignedTo') && e('closed,closed'); // 步骤1：正常情况
r($result2) && p('count') && e('3'); // 步骤2：验证返回数组数量
r($result3) && p('3:closedReason') && e('postponed'); // 步骤3：postponed关闭原因
r($result4) && p('3:plan') && e('~~'); // 步骤4：postponed原因清空plan
r($result5) && p('1:stage') && e('closed'); // 步骤5：stage字段设置为closed