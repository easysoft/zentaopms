#!/usr/bin/env php
<?php

/**

title=运营界面项目团队管理
timeout=0
cid=1

- 添加项目团队成员
 - 测试结果 @项目团队成员添加成功
 - 最终测试状态 @SUCCESS
- 删除项目已有的团队成员
 - 测试结果 @项目团队成员删除成功
 - 最终测试状态 @SUCCESS
- 复制部门成员
 - 测试结果 @复制部门团队成员成功
 - 最终测试状态 @SUCCESS

 */

chdir(__DIR__);
include '../lib/managemembersforlite.ui.class.php';
global $config;

$user = zenData('user');
$user->id->range('1-100');
$user->dept->range('1{3}, 2{1}');
$user->account->range('admin, user1, user2, user3');
$user->realname->range('admin, 用户1, 用户2, 用户3');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(4);

$team = zenData('team');
$team->id->range('1');
$team->root->range('1');
$team->type->range('project');
$team->account->range('admin');
$team->days->range('7');
$team->gen(1);
