#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDataOfStoriesPerAssignedTo();
timeout=0
cid=0

- 步骤1：默认story类型统计 @4
- 步骤2：验证admin用户数据第admin条的value属性 @5
- 步骤3：验证用户名显示第admin条的name属性 @admin
- 步骤4：验证其他用户数据第user2条的value属性 @5
- 步骤5：测试requirement类型统计 @4
- 步骤6：测试空数据情况 @0
- 步骤7：测试空指派统计 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 准备测试数据
zenData('story')->loadYaml('story_getdataofstoriesperassignedto', false, 2)->gen(20);
zenData('user')->loadYaml('user_getdataofstoriesperassignedto', false, 2)->gen(10);

su('admin');

// 创建测试实例
$storyTest = new storyTest();

// 设置查询条件
$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` <= 20";

// 测试步骤1：测试默认story类型的统计结果，返回指派人分组数量
r(count($storyTest->getDataOfStoriesPerAssignedToTest())) && p() && e('4'); // 步骤1：默认story类型统计

// 测试步骤2：验证admin用户的需求数量
r($storyTest->getDataOfStoriesPerAssignedToTest()) && p('admin:value') && e('5'); // 步骤2：验证admin用户数据

// 测试步骤3：验证admin用户的显示名称
r($storyTest->getDataOfStoriesPerAssignedToTest()) && p('admin:name') && e('admin'); // 步骤3：验证用户名显示

// 测试步骤4：验证user2用户的需求数量
r($storyTest->getDataOfStoriesPerAssignedToTest()) && p('user2:value') && e('5'); // 步骤4：验证其他用户数据

// 测试步骤5：测试requirement类型的统计数量
r(count($storyTest->getDataOfStoriesPerAssignedToTest('requirement'))) && p() && e('4'); // 步骤5：测试requirement类型统计

// 测试步骤6：测试空数据情况
$_SESSION['storyQueryCondition'] = "`id` > 100";
r(count($storyTest->getDataOfStoriesPerAssignedToTest())) && p() && e('0'); // 步骤6：测试空数据情况

// 恢复查询条件
$_SESSION['storyQueryCondition'] = "`id` <= 20";

// 测试步骤7：验证空指派字段的存在性
$result = $storyTest->getDataOfStoriesPerAssignedToTest();
r(isset($result[''])) && p() && e('0'); // 步骤7：测试空指派统计