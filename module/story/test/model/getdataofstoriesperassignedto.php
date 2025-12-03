#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDataOfStoriesPerAssignedTo();
timeout=0
cid=18512

- 测试story类型返回指派人第admin条的name属性 @管理员
- 测试admin用户的需求数量统计值为5第admin条的value属性 @5
- 测试admin用户显示名称为真实姓名第admin条的name属性 @管理员
- 测试user2用户的需求数量统计值为3第user2条的value属性 @3
- 测试requirement类型返回指派人第admin条的name属性 @管理员
- 测试无效类型情况下返回空数组 @5
- 测试空指派用户的统计数量为2属性:value @~~

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

global $tester;
$tester->loadModel('report');

// 创建测试实例
$storyTest = new storyTest();

r($storyTest->getDataOfStoriesPerAssignedToTest('story')) && p('admin:name') && e('管理员'); // 测试story类型返回指派人

r($storyTest->getDataOfStoriesPerAssignedToTest('story')) && p('admin:value') && e('5'); // 测试admin用户的需求数量统计值为5

r($storyTest->getDataOfStoriesPerAssignedToTest('story')) && p('admin:name') && e('管理员'); // 测试admin用户显示名称为真实姓名

r($storyTest->getDataOfStoriesPerAssignedToTest('story')) && p('user2:value') && e('3'); // 测试user2用户的需求数量统计值为3

r($storyTest->getDataOfStoriesPerAssignedToTest('requirement')) && p('admin:name') && e('管理员'); // 测试requirement类型返回指派人

r(count($storyTest->getDataOfStoriesPerAssignedToTest('nonexistent'))) && p() && e('5'); // 测试无效类型情况下返回空数组

r($storyTest->getDataOfStoriesPerAssignedToTest('story')) && p(':value') && e('~~'); // 测试空指派用户的统计数量为2