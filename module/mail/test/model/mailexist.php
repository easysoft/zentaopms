#!/usr/bin/env php
<?php

/**

title=测试 mailModel::mailExist();
timeout=0
cid=17014

- 执行mailTest模块的mailExistTest方法 属性email @admin@test.com
- 执行mailTest模块的mailExistTest方法 属性email @user2@test.com
- 执行mailTest模块的mailExistTest方法 属性email @
- 执行mailTest模块的mailExistTest方法 属性email @invalid-email
- 执行mailTest模块的mailExistTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$mailTest = new mailModelTest();

// 测试步骤1：有邮箱用户存在的情况
$user = zenData('user');
$user->account->range('admin');
$user->email->range('admin@test.com');
$user->realname->range('管理员');
$user->password->range('123456');
$user->role->range('admin');
$user->deleted->range('0');
$user->gen(1);

su('admin');

r($mailTest->mailExistTest()) && p('email') && e('admin@test.com');

// 测试步骤2：多个用户中找到第一个有邮箱的
$user = zenData('user');
$user->account->range('user1,user2');
$user->email->range(',user2@test.com');
$user->realname->range('用户1,用户2');
$user->password->range('123456{2}');
$user->role->range('dev{2}');
$user->deleted->range('0{2}');
$user->gen(2);

r($mailTest->mailExistTest()) && p('email') && e('user2@test.com');

// 测试步骤3：测试邮箱字段包含空格的情况（空格不等于空字符串，会返回对象）
$user = zenData('user');
$user->account->range('user3');
$user->email->range(' ');
$user->realname->range('用户3');
$user->password->range('123456');
$user->role->range('dev');
$user->deleted->range('0');
$user->gen(1);

r($mailTest->mailExistTest()) && p('email') && e(' ');

// 测试步骤4：测试多种无效邮箱格式但非空字符串
$user = zenData('user');
$user->account->range('user4');
$user->email->range('invalid-email');
$user->realname->range('用户4');
$user->password->range('123456');
$user->role->range('dev');
$user->deleted->range('0');
$user->gen(1);

r($mailTest->mailExistTest()) && p('email') && e('invalid-email');

// 测试步骤5：没有用户记录的情况，清空所有数据
$user = zenData('user');
$user->gen(0);

r($mailTest->mailExistTest()) && p() && e(0);