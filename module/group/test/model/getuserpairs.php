#!/usr/bin/env php
<?php

/**

title=测试 groupModel::getUserPairs();
timeout=0
cid=16717

- 步骤1：正常分组查询，验证admin用户属性admin @管理员
- 步骤2：同分组查询，验证user1用户属性user1 @用户1
- 步骤3：查询分组2，验证user2用户属性user2 @用户2
- 步骤4：查询分组2，验证user3用户属性user3 @用户3
- 步骤5：查询空分组，期望空数组 @0
- 步骤6：查询不存在分组，期望空数组 @0
- 步骤7：查询分组ID为0，期望空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 数据准备
$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,user3,user4,user5,user6');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6');
$userTable->deleted->range('0{6},1');
$userTable->visions->range(',rnd{7}');
$userTable->gen(7);

$groupTable = zenData('group');
$groupTable->id->range('1-5');
$groupTable->name->range('管理组,开发组,测试组,设计组,运营组');
$groupTable->gen(5);

$usergroupTable = zenData('usergroup');
$usergroupTable->account->range('admin,user1,user2,user3,user4,user6');
$usergroupTable->group->range('1{2},2{2},3{1},5{1}');
$usergroupTable->gen(6);

// 用户登录
su('admin');

// 创建测试实例
$group = new groupModelTest();

r($group->getUserPairsTest(1)) && p('admin') && e('管理员');      // 步骤1：正常分组查询，验证admin用户
r($group->getUserPairsTest(1)) && p('user1') && e('用户1');      // 步骤2：同分组查询，验证user1用户
r($group->getUserPairsTest(2)) && p('user2') && e('用户2');      // 步骤3：查询分组2，验证user2用户
r($group->getUserPairsTest(2)) && p('user3') && e('用户3');      // 步骤4：查询分组2，验证user3用户
r($group->getUserPairsTest(4)) && p() && e('0');                // 步骤5：查询空分组，期望空数组
r($group->getUserPairsTest(100)) && p() && e('0');              // 步骤6：查询不存在分组，期望空数组
r($group->getUserPairsTest(0)) && p() && e('0');                // 步骤7：查询分组ID为0，期望空数组