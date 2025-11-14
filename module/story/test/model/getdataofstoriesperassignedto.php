#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDataOfStoriesPerAssignedTo();
timeout=0
cid=18512

- 执行storyTest模块的getDataOfStoriesPerAssignedToTest方法，参数是'story'  @array
- 执行storyTest模块的getDataOfStoriesPerAssignedToTest方法，参数是'story' 第admin条的value属性 @5
- 执行storyTest模块的getDataOfStoriesPerAssignedToTest方法，参数是'story' 第admin条的name属性 @管理员
- 执行storyTest模块的getDataOfStoriesPerAssignedToTest方法，参数是'story' 第user2条的value属性 @3
- 执行storyTest模块的getDataOfStoriesPerAssignedToTest方法，参数是'requirement'  @array
- 执行storyTest模块的getDataOfStoriesPerAssignedToTest方法，参数是'nonexistent'  @0
- 执行storyTest模块的getDataOfStoriesPerAssignedToTest方法，参数是'story' 属性:value @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 清理session条件，确保测试环境干净
unset($_SESSION['storyOnlyCondition']);
unset($_SESSION['storyQueryCondition']);

// 准备story表测试数据
$storyTable = zenData('story');
$storyTable->id->range('1-15');
$storyTable->product->range('1');
$storyTable->title->range('测试需求1,测试需求2,测试需求3,测试需求4,测试需求5,测试需求6,测试需求7,测试需求8,测试需求9,测试需求10,测试需求11,测试需求12,测试需求13,测试需求14,测试需求15');
$storyTable->type->range('story{10},requirement{3},epic{2}');
$storyTable->assignedTo->range('admin{5},user1{2},user2{3},user3{3},[]{2}');
$storyTable->status->range('active{12},closed{3}');
$storyTable->deleted->range('0');
$storyTable->gen(15);

// 准备user表测试数据
$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,user3,test1,test2,test3,test4,test5,test6');
$userTable->realname->range('管理员,用户一,用户二,用户三,测试用户1,测试用户2,测试用户3,测试用户4,测试用户5,测试用户6');
$userTable->deleted->range('0');
$userTable->gen(10);

// 模拟管理员登录
su('admin');

// 创建测试实例
$storyTest = new storyTest();

// 测试步骤1：测试story类型返回指派人统计数据结构为数组
r($storyTest->getDataOfStoriesPerAssignedToTest('story')) && p() && e('array');

// 测试步骤2：验证admin用户的需求数量统计值为5
r($storyTest->getDataOfStoriesPerAssignedToTest('story')) && p('admin:value') && e('5');

// 测试步骤3：验证admin用户显示名称为真实姓名
r($storyTest->getDataOfStoriesPerAssignedToTest('story')) && p('admin:name') && e('管理员');

// 测试步骤4：验证user2用户的需求数量统计值为3
r($storyTest->getDataOfStoriesPerAssignedToTest('story')) && p('user2:value') && e('3');

// 测试步骤5：测试requirement类型数据统计结构为数组
r($storyTest->getDataOfStoriesPerAssignedToTest('requirement')) && p() && e('array');

// 测试步骤6：测试无效类型情况下返回空数组
r(count($storyTest->getDataOfStoriesPerAssignedToTest('nonexistent'))) && p() && e('0');

// 测试步骤7：验证空指派用户的统计数量为2
r($storyTest->getDataOfStoriesPerAssignedToTest('story')) && p(':value') && e('2');