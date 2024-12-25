#!/usr/bin/env php
<?php

/**

title=干系人沟通记录测试
timeout=0
cid=4

- 沟通记录保存成功
 - 测试结果 @沟通记录保存成功
 - 最终测试状态 @ SUCCESS

*/

chdir(__DIR__);
include '../lib/communicate.ui.class.php';
global $config;

$action = zenData('action');
$action->gen(0);

$stakeholder = zenData('stakeholder');
$stakeholder->id->range('1');
$stakeholder->objectID->range('1');
$stakeholder->objectType->range('project');
$stakeholder->user->range('user1');
$stakeholder->type->range('inside');
$stakeholder->key->range('0');
$stakeholder->from->range('company');
$stakeholder->gen(1);

$user = zenData('user');
$user->id->range('1-5');
$user->type->range('inside{4}, outside{1}');
$user->dept->range('1');
$user->account->range('admin, user1, user2, user3, user4');
$user->realname->range('admin, user1, user2, user3, user4');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->visions->range('rnd');
$user->gen(5);
