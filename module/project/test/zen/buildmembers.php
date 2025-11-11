#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildMembers();
timeout=0
cid=0

- 步骤1:正常情况3个成员+5个空行
 - 属性count @9
 - 属性hasDefault @1
 - 属性hasImport @1
 - 属性hasDept @2
- 步骤2:只有当前成员
 - 属性count @6
 - 属性hasDefault @1
 - 属性hasImport @0
 - 属性hasDept @0
- 步骤3:部门成员与当前成员重复
 - 属性count @7
 - 属性hasDefault @1
 - 属性hasDept @1
- 步骤4:部门成员与导入成员重复
 - 属性count @8
 - 属性hasDefault @1
 - 属性hasImport @1
 - 属性hasDept @1
- 步骤5:所有数组为空
 - 属性count @5
 - 属性hasDefault @0
 - 属性hasImport @0
 - 属性hasDept @0
 - 属性hasAdd @5
- 步骤6:成员类型标记属性memberTypes @default|dept|import|add
- 步骤7:混合数据场景
 - 属性count @6
 - 属性hasDept @1
 - 属性hasAdd @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

su('admin');

$projectTest = new projectZenTest();

r($projectTest->buildMembersTest(array('user1' => (object)array('account' => 'user1', 'role' => 'dev', 'days' => 10, 'hours' => 8, 'limited' => 'no')), array('user2' => (object)array('account' => 'user2', 'role' => 'qa')), array('user3' => 'User3', 'user4' => 'User4'), 10)) && p('count,hasDefault,hasImport,hasDept') && e('9,1,1,2'); // 步骤1:正常情况3个成员+5个空行
r($projectTest->buildMembersTest(array('user1' => (object)array('account' => 'user1', 'role' => 'dev')), array(), array(), 5)) && p('count,hasDefault,hasImport,hasDept') && e('6,1,0,0'); // 步骤2:只有当前成员
r($projectTest->buildMembersTest(array('user1' => (object)array('account' => 'user1', 'role' => 'dev')), array(), array('user1' => 'User1', 'user2' => 'User2'), 10)) && p('count,hasDefault,hasDept') && e('7,1,1'); // 步骤3:部门成员与当前成员重复
r($projectTest->buildMembersTest(array('user1' => (object)array('account' => 'user1', 'role' => 'dev')), array('user2' => (object)array('account' => 'user2', 'role' => 'qa')), array('user2' => 'User2', 'user3' => 'User3'), 10)) && p('count,hasDefault,hasImport,hasDept') && e('8,1,1,1'); // 步骤4:部门成员与导入成员重复
r($projectTest->buildMembersTest(array(), array(), array(), 0)) && p('count,hasDefault,hasImport,hasDept,hasAdd') && e('5,0,0,0,5'); // 步骤5:所有数组为空
r($projectTest->buildMembersTest(array('admin' => (object)array('account' => 'admin', 'role' => 'pm')), array('dev1' => (object)array('account' => 'dev1', 'role' => 'dev')), array('qa1' => 'QA1'), 5)) && p('memberTypes') && e('default|dept|import|add'); // 步骤6:成员类型标记
r($projectTest->buildMembersTest(array(), array(), array('user1' => 'User1'), 15)) && p('count,hasDept,hasAdd') && e('6,1,5'); // 步骤7:混合数据场景