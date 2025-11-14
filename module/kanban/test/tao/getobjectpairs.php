#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getObjectPairs();
timeout=0
cid=16983

- 步骤1：正常获取模块键值对
 - 属性1 @模块一
 - 属性2 @模块二
 - 属性3 @模块三
- 步骤2：正常获取需求键值对
 - 属性1 @需求一
 - 属性2 @需求二
 - 属性3 @需求三
- 步骤3：正常获取用户键值对
 - 属性admin @管理员
 - 属性user1 @用户一
 - 属性user2 @用户二
- 步骤4：获取优先级键值对
 - 属性1 @1
 - 属性2 @2
 - 属性3 @3
- 步骤5：空groupByList的情况 @所属模块: 无

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户一,用户二,用户三,用户四,用户五,用户六,用户七,用户八,用户九');
$user->deleted->range('0');
$user->gen(10);

$module = zenData('module');
$module->id->range('1-10');
$module->name->range('模块一,模块二,模块三,模块四,模块五,模块六,模块七,模块八,模块九,模块十');
$module->type->range('story,task,bug');
$module->deleted->range('0');
$module->gen(10);

$story = zenData('story');
$story->id->range('1-10');
$story->title->range('需求一,需求二,需求三,需求四,需求五,需求六,需求七,需求八,需求九,需求十');
$story->deleted->range('0');
$story->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->getObjectPairsTest('module', array(1, 2, 3), 'story', 'id_asc')) && p('1,2,3') && e('模块一,模块二,模块三'); // 步骤1：正常获取模块键值对
r($kanbanTest->getObjectPairsTest('story', array(1, 2, 3), 'task', 'id_asc')) && p('1,2,3') && e('需求一,需求二,需求三'); // 步骤2：正常获取需求键值对
r($kanbanTest->getObjectPairsTest('assignedTo', array('admin', 'user1', 'user2'), 'bug', 'id_asc')) && p('admin,user1,user2') && e('管理员,用户一,用户二'); // 步骤3：正常获取用户键值对
r($kanbanTest->getObjectPairsTest('pri', array(1, 2, 3), 'story', 'id_asc')) && p('1,2,3') && e('1,2,3'); // 步骤4：获取优先级键值对
r($kanbanTest->getObjectPairsTest('module', array(), 'story', 'id_asc')) && p('0') && e('所属模块: 无'); // 步骤5：空groupByList的情况