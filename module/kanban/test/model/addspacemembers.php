#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::addSpaceMembers();
cid=16870

- 测试步骤1：正常添加成员到whitelist >> 期望成功添加新成员
- 测试步骤2：正常添加成员到team >> 期望成功添加新成员
- 测试步骤3：重复添加已存在成员 >> 期望不重复添加
- 测试步骤4：添加空成员数组 >> 期望无任何变化
- 测试步骤5：处理不存在的空间ID >> 期望无操作且无报错
- 测试步骤6：测试边界值情况 >> 期望正确处理特殊输入
- 测试步骤7：验证成员添加的完整性 >> 期望所有新成员都被正确添加

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('kanbanspace');
$table->id->range('1-3');
$table->name->range('测试空间1,测试空间2,测试空间3');
$table->type->range('private,cooperation,public');
$table->owner->range('admin,user1,user2');
$table->team->range('user3,po15,user4');
$table->whitelist->range('user3,po15,user4');
$table->status->range('active{3}');
$table->gen(3);

su('admin');

$kanbanTest = new kanbanModelTest();

r($kanbanTest->addSpaceMembersTest(1, 'whitelist', array('admin', 'po1', 'dev1', 'qa1'))) && p('fieldValue') && e('user3,admin,po1,dev1,qa1');
r($kanbanTest->addSpaceMembersTest(2, 'team', array('admin', 'po1', 'dev1', 'qa1'))) && p('fieldValue') && e('po15,admin,po1,dev1,qa1');
r($kanbanTest->addSpaceMembersTest(1, 'whitelist', array('admin', 'user3'))) && p('fieldValue') && e('user3,admin,po1,dev1,qa1');
r($kanbanTest->addSpaceMembersTest(3, 'team', array())) && p('fieldValue') && e('user4');
r($kanbanTest->addSpaceMembersTest(999, 'whitelist', array('test1', 'test2'))) && p('fieldValue') && e('~~');
r($kanbanTest->addSpaceMembersTest(1, 'team', array('specialuser', '', 'normaluser'))) && p('fieldValue') && e('user3,specialuser,,normaluser');
r($kanbanTest->addSpaceMembersTest(3, 'whitelist', array('newuser1', 'newuser2', 'newuser3'))) && p('fieldValue') && e('user4,newuser1,newuser2,newuser3');