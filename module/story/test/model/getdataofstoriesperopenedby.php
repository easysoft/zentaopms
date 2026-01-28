#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDataOfStoriesPerOpenedBy();
timeout=0
cid=18518

- 步骤1：正常story类型参数，返回4个分组 @4
- 步骤2：epic类型参数，没有epic数据 @0
- 步骤3：requirement类型参数，没有requirement数据 @0
- 步骤4：空数据测试，返回空数组 @0
- 步骤5：测试返回数据结构，验证name和value字段
 - 第user2条的name属性 @用户2
 - 第user2条的value属性 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
zenData("user")->gen(5);
$story = zenData('story');
$story->openedBy->range('user1{3},user2{5},user3{4},user4{8}');
$story->type->range('story');
$story->deleted->range('0');
$story->version->range('1');
$story->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyModelTest();

// 5. 设置查询条件
$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`deleted` = '0'";

// 6. 执行测试步骤 - 必须包含至少5个测试步骤
r(count($storyTest->getDataOfStoriesPerOpenedByTest('story'))) && p() && e('4'); // 步骤1：正常story类型参数，返回4个分组

// 测试不同storyType，通过设置对应的session变量
$_SESSION['epicOnlyCondition']  = true;
$_SESSION['epicQueryCondition'] = "`type` = 'epic' AND `deleted` = '0'";
r(count($storyTest->getDataOfStoriesPerOpenedByTest('epic'))) && p() && e('0'); // 步骤2：epic类型参数，没有epic数据

$_SESSION['requirementOnlyCondition']  = true;
$_SESSION['requirementQueryCondition'] = "`type` = 'requirement' AND `deleted` = '0'";
r(count($storyTest->getDataOfStoriesPerOpenedByTest('requirement'))) && p() && e('0'); // 步骤3：requirement类型参数，没有requirement数据

// 清空数据后测试空结果
zenData('story')->gen(0);
r(count($storyTest->getDataOfStoriesPerOpenedByTest('story'))) && p() && e('0'); // 步骤4：空数据测试，返回空数组

// 恢复数据测试数据结构和用户映射
zenData('story');
$story = zenData('story');
$story->openedBy->range('user1{3},user2{5},user3{4},user4{8}');
$story->type->range('story');
$story->deleted->range('0');
$story->version->range('1');
$story->gen(20);

$result = $storyTest->getDataOfStoriesPerOpenedByTest('story');
r($result) && p('user2:name,value') && e('用户2,5'); // 步骤5：测试返回数据结构，验证name和value字段