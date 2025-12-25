#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildMembers();
timeout=0
cid=16416

- 执行$members['days[1]'] @可用工日不能大于执行的可用工日『20』
- 执行$members @3
- 执行$members[0]
 - 属性account @admin
 - 属性days @30
 - 属性type @execution
 - 属性root @1
- 执行$members[1]
 - 属性account @productManager
 - 属性days @20
 - 属性type @execution
 - 属性root @1
- 执行$members[2]
 - 属性account @projectManager
 - 属性days @10
 - 属性type @execution
 - 属性root @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$execution = zenData('project');
$execution->type->range('stage');
$execution->gen(5);
$team = zenData('team');
$team->root->range('1');
$team->type->range('execution');
$team->account->range('admin,test1,test2,user1,user2,user3');
$team->days->range('30');
$team->limited->range('no');
$team->gen(3);
$user = zenData('user');
$user->account->range('admin,test1,test2,user1,user2,user3');
$user->realname->range('管理员,测试1,测试2,用户1,用户2,用户3');
$user->role->range('admin,qa,dev,pm,po,td');
$user->deleted->range('0');
$user->gen(6);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
global $app;
$app->moduleName = 'execution';
$app->methodName = 'managemembers';
$executionTest = new executionZenTest();

// 5. 准备测试数据
// 准备当前成员数据
$membersData[0]['realname'] = '管理员';
$membersData[0]['account']  = 'admin';
$membersData[0]['role']     = '';
$membersData[0]['days']     = '30';
$membersData[0]['hours']    = '7.0';
$membersData[0]['limited']  = 'no';
$membersData[1]['realname'] = '产品经理';
$membersData[1]['account']  = 'productManager';
$membersData[1]['role']     = '';
$membersData[1]['days']     = '30';
$membersData[1]['hours']    = '7.0';
$membersData[1]['limited']  = 'no';

// 6. 🔴 强制要求：必须包含至少5个测试步骤
$members = $executionTest->buildMembersForManageMembersTest(1, $membersData);
r($members['days[1]']) && p() && e('可用工日不能大于执行的可用工日『20』');

$membersData[1]['realname'] = '产品经理';
$membersData[1]['account']  = 'productManager';
$membersData[1]['role']     = '';
$membersData[1]['days']     = '20';
$membersData[1]['hours']    = '7.0';
$membersData[1]['limited']  = 'no';
$membersData[2]['realname'] = '项目经理';
$membersData[2]['account']  = 'projectManager';
$membersData[2]['role']     = '';
$membersData[2]['days']     = '10';
$membersData[2]['hours']    = '7.0';
$membersData[2]['limited']  = 'no';

$members = $executionTest->buildMembersForManageMembersTest(1, $membersData);
r(count($members)) && p() && e('3');
r($members[0])     && p('account,days,type,root') && e('admin,30,execution,1');
r($members[1])     && p('account,days,type,root') && e('productManager,20,execution,1');
r($members[2])     && p('account,days,type,root') && e('projectManager,10,execution,1');
