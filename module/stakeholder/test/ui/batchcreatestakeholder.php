#!/usr/bin/env php
<?php

/**

title=批量创建干系人测试
timeout=0
cid=2

- 通过复制部门人员来批量创建干系人
 - 测试结果 @批量创建干系人成功
 - 最终测试状态 @ SUCCESS
- 通过从父项目集导入来批量创建干系人
 - 测试结果 @批量创建干系人成功
 - 最终测试状态 @ SUCCESS
- 批量删除干系人
 - 测试结果 @批量删除干系人成功
 - 最终测试状态 @ SUCCESS

*/

chdir(__DIR__);
include '../lib/batchcreatestakeholder.ui.class.php';
global $config;

$stakeholder = zenData('stakeholder');
$stakeholder->id->range('1');
$stakeholder->objectID->range('1');
$stakeholder->objectType->range('program');
$stakeholder->user->range('user3');
$stakeholder->type->range('inside');
$stakeholder->from->range('company');
$stakeholder->gen(1);

$dept =zenData('dept');
$dept->id->range('1');
$dept->name->range('研发部');
$dept->parent->range('0');
$dept->path->range('`,1,`');
$dept->grade->range('1');
$dept->gen(1);

$user = zenData('user');
$user->id->range('1-5');
$user->type->range('inside{4}, outside{1}');
$user->dept->range('1{2}, 0{2}');
$user->account->range('admin, user1, user2, user3, user4');
$user->realname->range('admin, user1, user2, user3, user4');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->visions->range('rnd');
$user->gen(5);
