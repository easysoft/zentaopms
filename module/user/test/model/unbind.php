#!/usr/bin/env php
<?php

/**

title=测试 userModel::unbind();
timeout=0
cid=19657

- 步骤1：解绑已有然之账号的正常用户 @success
- 步骤2：解绑不存在的用户账号 @success
- 步骤3：解绑已经为空的然之账号 @success
- 步骤4：解绑空字符串账号参数 @success
- 步骤5：解绑包含特殊字符的账号 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->id->range('1-10');
$table->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$table->password->range('a0933c1218a4e745bacdcf572b10eba7');
$table->realname->range('1-10')->prefix('用户');
$table->ranzhi->range('admin,user1,user2,user3,,user5,user6,user7,,user9'); // 包含空值和有值的情况
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($userTest->unbindTest('user1')) && p('') && e('success'); // 步骤1：解绑已有然之账号的正常用户
r($userTest->unbindTest('test999')) && p('') && e('success'); // 步骤2：解绑不存在的用户账号
r($userTest->unbindTest('user4')) && p('') && e('success'); // 步骤3：解绑已经为空的然之账号
r($userTest->unbindTest('')) && p('') && e('success'); // 步骤4：解绑空字符串账号参数
r($userTest->unbindTest('user@#$%')) && p('') && e('success'); // 步骤5：解绑包含特殊字符的账号