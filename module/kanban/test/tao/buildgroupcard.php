#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::buildGroupCard();
timeout=0
cid=16970

- 步骤1：正常情况下构建分组卡片 @2
- 步骤2：空卡片ID列表情况 @0
- 步骤3：无效卡片ID情况 @0
- 步骤4：按assignedTo分组测试 @2
- 步骤5：搜索过滤测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备
$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$storyTable->assignedTo->range('user1,user2,user1,user3,,user2,user1,,user3,user2');
$storyTable->module->range('1,2,1,3,2,1,3,2,1,3');
$storyTable->pri->range('1,2,3,1,2,3,1,2,3,1');
$storyTable->type->range('story,epic,requirement,story,epic,requirement,story,epic,requirement,story');
$storyTable->category->range('feature,interface,performance,feature,interface,performance,feature,interface,performance,feature');
$storyTable->source->range('customer,market,product,customer,market,product,customer,market,product,customer');
$storyTable->status->range('active,draft,reviewing,active,draft,reviewing,active,draft,reviewing,active');
$storyTable->gen(10);

$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10');
$taskTable->assignedTo->range('user1,user2,user1,user3,,user2,user1,,user3,user2');
$taskTable->module->range('1,2,1,3,2,1,3,2,1,3');
$taskTable->pri->range('1,2,3,1,2,3,1,2,3,1');
$taskTable->mode->range('linear{5},multi{5}');
$taskTable->gen(10);

$taskTeamTable = zenData('taskteam');
$taskTeamTable->id->range('1-10');
$taskTeamTable->task->range('6,7,8,9,10,6,7,8,9,10');
$taskTeamTable->account->range('user1,user2,user3,user1,user2,user3,user1,user2,user3,user1');
$taskTeamTable->gen(10);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户一,用户二,用户三,用户四');
$userTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 准备测试数据
$cardGroup = array(
    'story' => array(
        '1' => (object)array('id' => 1, 'title' => '需求1', 'assignedTo' => 'user1', 'module' => '1', 'pri' => '1', 'type' => 'story', 'category' => 'feature', 'source' => 'customer'),
        '2' => (object)array('id' => 2, 'title' => '需求2', 'assignedTo' => 'user2', 'module' => '2', 'pri' => '2', 'type' => 'epic', 'category' => 'interface', 'source' => 'market'),
        '3' => (object)array('id' => 3, 'title' => '需求3', 'assignedTo' => 'user1', 'module' => '1', 'pri' => '3', 'type' => 'requirement', 'category' => 'performance', 'source' => 'product')
    )
);

$cardIdList = array('1', '2', '3');
$emptyCardIdList = array();
$invalidCardIdList = array('999', '888');

$column = (object)array(
    'id' => 1,
    'columnType' => 'story',
    'lane' => 'lane1'
);

$avatarPairs = array(
    'user1' => '/path/to/avatar1.jpg',
    'user2' => '/path/to/avatar2.jpg',
    'user3' => '/path/to/avatar3.jpg'
);

$users = array(
    'user1' => '用户一',
    'user2' => '用户二',
    'user3' => '用户三'
);

$menus = array();

// 6. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->buildGroupCardTest($cardGroup, $cardIdList, $column, '1', 'module', 'story', '', $avatarPairs, $users, $menus)) && p('0') && e('2'); // 步骤1：正常情况下构建分组卡片
r($kanbanTest->buildGroupCardTest($cardGroup, $emptyCardIdList, $column, '1', 'module', 'story', '', $avatarPairs, $users, $menus)) && p('0') && e('0'); // 步骤2：空卡片ID列表情况  
r($kanbanTest->buildGroupCardTest($cardGroup, $invalidCardIdList, $column, '1', 'module', 'story', '', $avatarPairs, $users, $menus)) && p('0') && e('0'); // 步骤3：无效卡片ID情况
r($kanbanTest->buildGroupCardTest($cardGroup, $cardIdList, $column, 'user1', 'assignedTo', 'story', '', $avatarPairs, $users, $menus)) && p('0') && e('2'); // 步骤4：按assignedTo分组测试
r($kanbanTest->buildGroupCardTest($cardGroup, $cardIdList, $column, '1', 'module', 'story', '需求999', $avatarPairs, $users, $menus)) && p('0') && e('0'); // 步骤5：搜索过滤测试