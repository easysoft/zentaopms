#!/usr/bin/env php
<?php

/**

title=移除项目集下干系人测试
timeout=0
cid=73

- 单个移除干系人成功
 - 测试结果 @单个移除干系人成功
 - 最终测试状态 @ SUCCESS
- 批量移除干系人成功
 - 测试结果 @批量移除干系人成功
 - 最终测试状态 @ SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/unlinkstakeholder.ui.class.php';
global $config;

$stakeholder = zenData('stakeholder');
$stakeholder->id->range('1-3');
$stakeholder->objectID->range('1');
$stakeholder->objectType->range('program');
$stakeholder->user->range('user1, user2, user3');
$stakeholder->type->range('inside');
$stakeholder->key->range('0');
$stakeholder->from->range('company');
$stakeholder->gen(3);

$user = zenData('user');
$user->id->range('1-5');
$user->type->range('inside{4}, outside{1}');
$user->dept->range('1');
$user->account->range('admin, user1, user2, user3, user4');
$user->realname->range('admin, user1, user2, user3, user4');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->visions->range('rnd');
$user->gen(5);

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->type->range('program');
$project->model->range('[]');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('项目集A');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->begin->range('(-72w)-(-71w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+72w)-(+73w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(1);

$tester = new unlinkStakeholderTester();
$tester->login();

r($tester->unlinkStakeholder()) && p('message,status') && e('单个移除干系人成功, SUCCESS'); //单个移除干系人成功
r($tester->batchUnlinkStakeholders()) && p('message,status') && e('批量移除干系人成功, SUCCESS'); //批量移除干系人成功

$tester->closeBrowser();
