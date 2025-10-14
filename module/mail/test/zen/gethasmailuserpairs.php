#!/usr/bin/env php
<?php

/**

title=测试 mailZen::getHasMailUserPairs();
timeout=0
cid=0

- 步骤1：返回6个用户 @6
- 步骤2：admin用户格式 @管理员 admin@zentao.com
- 步骤3：user1用户格式 @测试用户1 user1@zentao.com
- 步骤4：不包含无邮箱用户 @0
- 步骤5：不包含已删除用户 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mailzen.unittest.class.php';

// 2. zendata数据准备（手动设置数据）
$user = zenData('user');
$user->id->range('1-10');
$user->company->range('1{10}');
$user->type->range('inside{10}');
$user->dept->range('1{10}');
$user->account->range('admin,user1,user2,user3,user4,user5,nomail1,nomail2,deleted1,deleted2');
$user->password->range('123456{10}');
$user->role->range('admin,qa,dev,pm,td,dev,qa,pm,admin,dev');
$user->realname->range('管理员,测试用户1,测试用户2,测试用户3,测试用户4,测试用户5,无邮箱用户1,无邮箱用户2,已删除用户1,已删除用户2');
$user->email->range('admin@zentao.com,user1@zentao.com,user2@zentao.com,user3@zentao.com,user4@zentao.com,user5@zentao.com,[],[],[],[]');
$user->deleted->range('0,0,0,0,0,0,0,0,1,1');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$mailTest = new mailZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
$result = $mailTest->getHasMailUserPairsZenTest();
r(count($result)) && p() && e('6'); // 步骤1：返回6个用户
r($result['admin']) && p() && e('管理员 admin@zentao.com'); // 步骤2：admin用户格式
r($result['user1']) && p() && e('测试用户1 user1@zentao.com'); // 步骤3：user1用户格式
r(isset($result['nomail1'])) && p() && e('0'); // 步骤4：不包含无邮箱用户
r(isset($result['deleted1'])) && p() && e('0'); // 步骤5：不包含已删除用户