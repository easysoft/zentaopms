#!/usr/bin/env php
<?php

/**

title=删除干系人测试
timeout=0
cid=6

- 删除干系人成功
 - 测试结果 @删除干系人成功
 - 最终测试状态 @ SUCCESS

*/

chdir(__DIR__);
include '../lib/browse.ui.class.php';
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
