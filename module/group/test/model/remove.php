#!/usr/bin/env php
<?php

/**

title=测试 groupModel::remove();
timeout=0
cid=16722

- 测试步骤1：删除存在的组ID @1
- 测试步骤2：删除不存在的组ID @1
- 测试步骤3：删除ID为0的无效组 @1
- 测试步骤4：删除负数ID的组 @1
- 测试步骤5：验证删除操作的完整性
 - 属性groupExists @0
 - 属性usergroupExists @0
 - 属性groupprivExists @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

// 准备测试数据
$group = zenData('group');
$group->id->range('1-5');
$group->name->range('测试组1,测试组2,测试组3,测试组4,测试组5');
$group->role->range('admin,user,qa,pm,dev');
$group->gen(5);

$usergroup = zenData('usergroup');
$usergroup->account->range('admin,user1,user2');
$usergroup->group->range('1-3');
$usergroup->gen(3);

$grouppriv = zenData('grouppriv');
$grouppriv->group->range('1-3');
$grouppriv->module->range('index,user,product');
$grouppriv->method->range('index,login,browse');
$grouppriv->gen(3);

su('admin');

$groupTest = new groupTest();

r($groupTest->removeTest(1)) && p() && e('1'); // 测试步骤1：删除存在的组ID
r($groupTest->removeTest(999)) && p() && e('1'); // 测试步骤2：删除不存在的组ID
r($groupTest->removeTest(0)) && p() && e('1'); // 测试步骤3：删除ID为0的无效组
r($groupTest->removeTest(-1)) && p() && e('1'); // 测试步骤4：删除负数ID的组
r($groupTest->verifyRemoveCompleteTest(2)) && p('groupExists,usergroupExists,groupprivExists') && e('0,0,0'); // 测试步骤5：验证删除操作的完整性