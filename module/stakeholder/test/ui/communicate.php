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
include '../lib/ui/communicate.ui.class.php';
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

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->auth->range('extend');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`');
$project->grade->range('1');
$project->name->range('敏捷项目1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(1);

$team = zendata('team');
$team->id->range('1');
$team->root->range('1');
$team->type->range('project');
$team->account->range('admin, user1, user2');
$team->join->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$team->gen(1);

$tester = new communicateTester();
$tester->login();

$stakeholder = array(
    array('comment' => '沟通记录'),
);

r($tester->communicate($stakeholder[0])) && p('message,status') && e('沟通记录保存成功, SUCCESS'); //沟通记录保存成功

$tester->closeBrowser();
