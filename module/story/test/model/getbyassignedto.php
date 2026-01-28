#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getByAssignedTo();
timeout=0
cid=18500

- 步骤1：查询admin用户被分配的story（产品1中有6个admin的story） @6
- 步骤2：查询不存在用户的story @0
- 步骤3：多产品全分支查询user1的story（user1有6个story） @6
- 步骤4：查询admin的story类型需求（产品1中admin有6个story类型） @6
- 步骤5：按ID倒序查询admin的story第15条的id属性 @15

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$productTable = zendata('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->type->range('normal');
$productTable->status->range('normal');
$productTable->deleted->range('0');
$productTable->gen(5);

$userTable = zendata('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$userTable->password->range('123456');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$userTable->deleted->range('0');
$userTable->gen(10);

$storyTable = zendata('story');
$storyTable->id->range('1-20');
$storyTable->product->range('1,1,1,1,1,2,2,2,2,2,1,1,1,1,1,2,2,2,2,2');
$storyTable->type->range('story,story,story,story,requirement,story,story,story,requirement,requirement,story,story,story,story,story,story,story,story,story,story');
$storyTable->assignedTo->range('admin,admin,user1,user1,admin,admin,user1,user2,admin,user1,admin,admin,user1,admin,admin,user1,user2,admin,user1,user2');
$storyTable->status->range('active');
$storyTable->deleted->range('0');
$storyTable->title->range('需求标题1,需求标题2,需求标题3,需求标题4,需求标题5,需求标题6,需求标题7,需求标题8,需求标题9,需求标题10,需求标题11,需求标题12,需求标题13,需求标题14,需求标题15,需求标题16,需求标题17,需求标题18,需求标题19,需求标题20');
$storyTable->vision->range('rnd');
$storyTable->gen(20);

su('admin');

$storyTest = new storyModelTest();

r(count($storyTest->getByAssignedToTest(1, 0, '', 'admin'))) && p() && e('6'); // 步骤1：查询admin用户被分配的story（产品1中有6个admin的story）
r(count($storyTest->getByAssignedToTest(1, 0, '', 'nonexist'))) && p() && e('0'); // 步骤2：查询不存在用户的story
r(count($storyTest->getByAssignedToTest(array(1, 2), 'all', '', 'user1'))) && p() && e('6'); // 步骤3：多产品全分支查询user1的story（user1有6个story）
r(count($storyTest->getByAssignedToTest(1, 0, '', 'admin', 'story'))) && p() && e('6'); // 步骤4：查询admin的story类型需求（产品1中admin有6个story类型）
r($storyTest->getByAssignedToTest(1, 0, '', 'admin', 'story', 'id_desc')) && p('15:id') && e('15'); // 步骤5：按ID倒序查询admin的story