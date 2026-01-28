#!/usr/bin/env php
<?php

/**

title=测试 userModel->getProductMembers();
timeout=0
cid=19623

- 产品列表为空，返回空数组。 @0
- 产品1关联项目1，项目1有团队成员user1和干系人user2，返回正确的团队成员和干系人。第1条的user1属性 @user1
- 执行$stakeholderGroups第1条的user2属性 @user2
- 产品2关联项目2，项目2有团队成员user3和干系人user4，返回正确的团队成员和干系人。第2条的user3属性 @user3
- 执行$stakeholderGroups第2条的user4属性 @user4
- 产品3未关联任何项目，获取不到。属性3 @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(4);
zenData('product')->gen(3);
zenData('project')->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-2');
$projectProduct->product->range('1-2');
$projectProduct->gen(2);

$team = zenData('team');
$team->root->range('1-2');
$team->type->range('project');
$team->account->range('user1,user3');
$team->gen(2);

$stakeholder = zenData('stakeholder');
$stakeholder->objectID->range('1-2');
$stakeholder->objectType->range('project');
$stakeholder->user->range('user2,user4');
$stakeholder->gen(2);

$userTest = new userModelTest();

$emptyProducts = $userTest->getProductMembersTest(array());
r(count($emptyProducts[0])) && p() && e(0); // 产品列表为空，返回空数组。

$products = array(
    1 => (object)array('id' => 1, 'program' => 0),
    2 => (object)array('id' => 2, 'program' => 0),
    3 => (object)array('id' => 3, 'program' => 0)
);

list($teamsGroup, $stakeholderGroups) = $userTest->getProductMembersTest($products);

r($teamsGroup) && p('1:user1') && e('user1'); // 产品1关联项目1，项目1有团队成员user1和干系人user2，返回正确的团队成员和干系人。
r($stakeholderGroups) && p('1:user2') && e('user2');

r($teamsGroup) && p('2:user3') && e('user3'); // 产品2关联项目2，项目2有团队成员user3和干系人user4，返回正确的团队成员和干系人。
r($stakeholderGroups) && p('2:user4') && e('user4');

r($teamsGroup) && p('3') && e('~~'); // 产品3未关联任何项目，获取不到。