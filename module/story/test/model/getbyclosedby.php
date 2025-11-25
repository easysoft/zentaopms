#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getByClosedBy();
timeout=0
cid=18501

- 步骤1：查询admin关闭的story（产品1中有5个admin关闭的story） @5
- 步骤2：查询不存在用户关闭的story @0
- 步骤3：查询空字符串关闭的story @0
- 步骤4：多产品查询user1关闭的story（user1关闭了9个story） @9
- 步骤5：按ID倒序查询admin关闭的story第1条的id属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

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
$storyTable->closedBy->range('admin,admin,user1,user1,admin,user1,user1,user2,admin,user1,,,,,,,,,');
$storyTable->status->range('closed,closed,closed,closed,closed,closed,closed,closed,closed,closed,active,active,active,active,active,active,active,active,active,active');
$storyTable->deleted->range('0');
$storyTable->title->range('需求标题1,需求标题2,需求标题3,需求标题4,需求标题5,需求标题6,需求标题7,需求标题8,需求标题9,需求标题10,需求标题11,需求标题12,需求标题13,需求标题14,需求标题15,需求标题16,需求标题17,需求标题18,需求标题19,需求标题20');
$storyTable->vision->range('rnd');
$storyTable->openedDate->range('`2024-01-01`{10},`2024-02-01`{10}');
$storyTable->closedDate->range('`2024-01-01`{5},`2024-01-02`{5}');
$storyTable->gen(20);

su('admin');

$storyTest = new storyTest();

r(count($storyTest->getByClosedByTest(1, 0, '', 'admin'))) && p() && e('5'); // 步骤1：查询admin关闭的story（产品1中有5个admin关闭的story）
r(count($storyTest->getByClosedByTest(1, 0, '', 'nonexist'))) && p() && e('0'); // 步骤2：查询不存在用户关闭的story
r(count($storyTest->getByClosedByTest(1, 0, '', ''))) && p() && e('0'); // 步骤3：查询空字符串关闭的story
r(count($storyTest->getByClosedByTest(array(1, 2), 'all', '', 'user1'))) && p() && e('9'); // 步骤4：多产品查询user1关闭的story（user1关闭了9个story）
r($storyTest->getByClosedByTest(1, 0, '', 'admin', 'story', 'id_desc')) && p('1:id') && e('1'); // 步骤5：按ID倒序查询admin关闭的story