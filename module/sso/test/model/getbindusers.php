#!/usr/bin/env php
<?php

/**

title=测试 ssoModel::getBindUsers();
timeout=0
cid=18405

- 步骤1：正常情况查询有ranzhi绑定的用户，期望返回4条记录 @4
- 步骤2：验证ranzhi1对应user1的映射关系属性ranzhi1 @user1
- 步骤3：验证ranzhi3对应user3的映射关系属性ranzhi3 @user3
- 步骤4：测试无ranzhi绑定用户情况，期望返回空数组 @0
- 步骤5：测试已删除用户不被返回，期望返回3条记录（排除已删除的user4和user5） @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->password->range('123456{10}');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->ranzhi->range('``,ranzhi1,ranzhi2,ranzhi3,ranzhi4,``,``,``,``,``');
$user->deleted->range('0{8},1{2}');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$ssoTest = new ssoModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $ssoTest->getBindUsersTest();
r(count($result1)) && p() && e('4'); // 步骤1：正常情况查询有ranzhi绑定的用户，期望返回4条记录
r($result1) && p('ranzhi1') && e('user1'); // 步骤2：验证ranzhi1对应user1的映射关系
r($result1) && p('ranzhi3') && e('user3'); // 步骤3：验证ranzhi3对应user3的映射关系

// 测试所有用户都没有ranzhi绑定的情况
$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->ranzhi->range('``{5}');
$user->deleted->range('0{5}');
$user->gen(5);

$result2 = $ssoTest->getBindUsersTest();
r(count($result2)) && p() && e('0'); // 步骤4：测试无ranzhi绑定用户情况，期望返回空数组

// 测试包含已删除用户的情况
$user = zenData('user');
$user->id->range('1-6');
$user->account->range('admin,user1,user2,user3,user4,user5');
$user->ranzhi->range('``,ranzhi1,ranzhi2,ranzhi3,ranzhi4,ranzhi5');
$user->deleted->range('0{4},1{2}');
$user->gen(6);

$result3 = $ssoTest->getBindUsersTest();
r(count($result3)) && p() && e('3'); // 步骤5：测试已删除用户不被返回，期望返回3条记录（排除已删除的user4和user5）