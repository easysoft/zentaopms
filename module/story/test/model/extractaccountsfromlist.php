#!/usr/bin/env php
<?php

/**

title=测试 storyModel::extractAccountsFromList();
timeout=0
cid=18490

- 测试步骤1：完整账户信息故事列表，应返回6个不同账户 @6
- 测试步骤2：包含空字段的故事列表，应返回4个有效账户 @4
- 测试步骤3：空故事列表，应返回0个账户 @0
- 测试步骤4：重复账户故事列表，应去重返回3个账户 @3
- 测试步骤5：单个故事，应返回4个账户 @4
- 测试步骤6：验证第一个账户为admin @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('story')->gen(10);

su('admin');

$storyTest = new storyTest();

// 测试数据1：包含完整账户信息的故事列表
$story1 = new stdClass();
$story1->openedBy = 'admin';
$story1->assignedTo = 'user1';
$story1->closedBy = 'user2';
$story1->lastEditedBy = 'user3';

$story2 = new stdClass();
$story2->openedBy = 'user4';
$story2->assignedTo = 'user5';
$story2->closedBy = 'admin';
$story2->lastEditedBy = 'user1';

$stories1 = array($story1, $story2);

// 测试数据2：包含空字段的故事列表
$story3 = new stdClass();
$story3->openedBy = 'admin';
$story3->assignedTo = '';
$story3->closedBy = 'user1';
$story3->lastEditedBy = '';

$story4 = new stdClass();
$story4->openedBy = '';
$story4->assignedTo = 'user2';
$story4->closedBy = '';
$story4->lastEditedBy = 'user3';

$stories2 = array($story3, $story4);

// 测试数据3：空故事列表
$stories3 = array();

// 测试数据4：包含重复账户的故事列表
$story5 = new stdClass();
$story5->openedBy = 'admin';
$story5->assignedTo = 'admin';
$story5->closedBy = 'user1';
$story5->lastEditedBy = 'user1';

$story6 = new stdClass();
$story6->openedBy = 'admin';
$story6->assignedTo = 'user1';
$story6->closedBy = 'admin';
$story6->lastEditedBy = 'user2';

$stories4 = array($story5, $story6);

// 测试数据5：单个故事
$story7 = new stdClass();
$story7->openedBy = 'testuser';
$story7->assignedTo = 'assignee';
$story7->closedBy = 'closer';
$story7->lastEditedBy = 'editor';

$stories5 = array($story7);

r(count($storyTest->extractAccountsFromListTest($stories1))) && p() && e('6'); // 测试步骤1：完整账户信息故事列表，应返回6个不同账户
r(count($storyTest->extractAccountsFromListTest($stories2))) && p() && e('4'); // 测试步骤2：包含空字段的故事列表，应返回4个有效账户
r(count($storyTest->extractAccountsFromListTest($stories3))) && p() && e('0'); // 测试步骤3：空故事列表，应返回0个账户
r(count($storyTest->extractAccountsFromListTest($stories4))) && p() && e('3'); // 测试步骤4：重复账户故事列表，应去重返回3个账户
r(count($storyTest->extractAccountsFromListTest($stories5))) && p() && e('4'); // 测试步骤5：单个故事，应返回4个账户
r($storyTest->extractAccountsFromListTest($stories1)) && p('0') && e('admin'); // 测试步骤6：验证第一个账户为admin