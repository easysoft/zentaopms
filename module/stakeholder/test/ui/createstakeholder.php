#!/usr/bin/env php
<?php

/**

title=创建干系人测试
timeout=0
cid=1

- 校验用户不能为空
 - 测试结果 @创建干系人表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 创建项目团队成员干系人
 - 测试结果 @创建干系人成功
 - 最终测试状态 @ SUCCESS
- 创建公司干系人
 - 测试结果 @创建干系人成功
 - 最终测试状态 @ SUCCESS
- 创建关键干系人
 - 测试结果 @创建干系人成功
 - 最终测试状态 @ SUCCESS
- 创建外部干系人
 - 测试结果 @创建干系人成功
 - 最终测试状态 @ SUCCESS

*/

chdir(__DIR__);
include '../lib/createstakeholder.ui.class.php';
global $config;

$stakeholder = zenData('stakeholder');
$stakeholder->gen(0);

$user = zenData('user');
$user->id->range('1-5');
$user->type->range('inside{4}, outside{1}');
$user->dept->range('1');
$user->account->range('admin, user1, user2, user3, user4');
$user->realname->range('admin, user1, user2, user3, user4');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(5);
