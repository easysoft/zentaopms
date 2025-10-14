#!/usr/bin/env php
<?php

/**

title=测试 kanbanZen::setUserAvatar();
timeout=0
cid=0

- 步骤1：正常情况，验证admin用户数据第admin条的realname属性 @管理员
- 步骤2：验证用户头像信息第user1条的avatar属性 @avatar2.jpg
- 步骤3：验证closed用户条目第closed条的realname属性 @Closed
- 步骤4：验证closed用户头像为空第closed条的avatar属性 @~~
- 步骤5：验证其他用户数据完整性第user2条的realname属性 @用户2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->password->range('123456{10}');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->email->range('admin@test.com,user1@test.com,user2@test.com,user3@test.com,user4@test.com,user5@test.com,user6@test.com,user7@test.com,user8@test.com,user9@test.com');
$user->deleted->range('0{10}');
$user->avatar->range('avatar1.jpg,avatar2.jpg,avatar3.jpg,avatar4.jpg,avatar5.jpg,{5}');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($kanbanTest->setUserAvatarTest()) && p('admin:realname') && e('管理员'); // 步骤1：正常情况，验证admin用户数据
r($kanbanTest->setUserAvatarTest()) && p('user1:avatar') && e('avatar2.jpg'); // 步骤2：验证用户头像信息
r($kanbanTest->setUserAvatarTest()) && p('closed:realname') && e('Closed'); // 步骤3：验证closed用户条目
r($kanbanTest->setUserAvatarTest()) && p('closed:avatar') && e('~~'); // 步骤4：验证closed用户头像为空
r($kanbanTest->setUserAvatarTest()) && p('user2:realname') && e('用户2'); // 步骤5：验证其他用户数据完整性