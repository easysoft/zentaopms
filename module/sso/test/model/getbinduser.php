#!/usr/bin/env php
<?php

/**

title=测试 ssoModel::getBindUser();
timeout=0
cid=18404

- 步骤1：正常查询存在的ranzhi绑定用户属性account @admin
- 步骤2：空字符串参数查询 @0
- 步骤3：查询不存在的ranzhi账号 @0
- 步骤4：查询已删除用户的ranzhi账号 @0
- 步骤5：长字符串ranzhi账号查询属性account @special_user
- 步骤6：查询另一个有效用户属性account @user1
- 步骤7：查询第三个有效用户属性account @user2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$user = zenData('user');
$user->id->range('1-8');
$user->account->range('admin,user1,user2,user3,user4,deleted_user,special_user,long_user');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,已删除用户,特殊用户,长用户');
$user->ranzhi->range('ranzhi_admin,ranzhi_user1,,ranzhi_user3,ranzhi_user4,deleted_ranzhi,ranzhi_special,long_ranzhi_account');
$user->deleted->range('0,0,0,0,0,1,0,0');
$user->gen(8);

// 用户登录
su('admin');

// 创建测试实例
$ssoTest = new ssoModelTest();

r($ssoTest->getBindUserTest('ranzhi_admin')) && p('account') && e('admin'); // 步骤1：正常查询存在的ranzhi绑定用户
r($ssoTest->getBindUserTest('')) && p('0') && e('0'); // 步骤2：空字符串参数查询
r($ssoTest->getBindUserTest('nonexistent_ranzhi')) && p('0') && e('0'); // 步骤3：查询不存在的ranzhi账号
r($ssoTest->getBindUserTest('ranzhi_special')) && p('0') && e('0'); // 步骤4：查询已删除用户的ranzhi账号
r($ssoTest->getBindUserTest('long_ranzhi_account')) && p('account') && e('special_user'); // 步骤5：长字符串ranzhi账号查询
r($ssoTest->getBindUserTest('ranzhi_user1')) && p('account') && e('user1'); // 步骤6：查询另一个有效用户
r($ssoTest->getBindUserTest('ranzhi_user3')) && p('account') && e('user2'); // 步骤7：查询第三个有效用户