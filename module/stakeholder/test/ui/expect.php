#!/usr/bin/env php
<?php

/**

title=干系人期望记录测试
timeout=0
cid=5

- 校验期望内容不能为空
 - 测试结果 @期望内容表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 校验达成进展不能为空
 - 测试结果 @期望内容表单页提示信息正确
 - 最终测试状态 @ SUCCESS
- 检查期望内容信息
 - 测试结果 @期望记录信息保存成功
 - 最终测试状态 @ SUCCESS

*/

chdir(__DIR__);
include '../lib/expect.ui.class.php';
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
