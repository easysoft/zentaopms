#!/usr/bin/env php
<?php

/**

title=项目团队管理
timeout=0
cid=1

- 添加项目团队成员测试结果 @项目团队成员添加成功
- 删除项目已有的团队成员最终测试状态 @SUCCESS
- 复制部门成员最终测试状态 @SUCCESS

 */

chdir(__DIR__);
include '../lib/managemembers.ui.class.php';
global $config;

$user = zenData('user');
$user->id->range('1-5');
$user->dept->range('1');
$user->account->range('admin, user1, user2, user3, user4');
$user->realname->range('admin, 用户1, 用户2, 用户3, 用户4');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(5);

$team = zenData('team');
$team->id->range('1');
$team->root->range('1');
$team->type->range('project');
$team->account->range('admin');
$team->days->range('7');
$team->hours->range('4');
$team->gen(1);

zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('product')->loadYaml('product', false, 1)->gen(1);
zendata('projectproduct')->loadYaml('projectproduct', false, 1)->gen(1);
zendata('dept')->loadYaml('dept', false, 1)->gen(1);
$tester = new manageMembersTester();
$tester->login();

//设置敏捷项目执行数据
$members = array(
    array('account' => '用户1', 'role' => '开发人员', 'day' => '7', 'hours' => '3'),
);

r($tester->addMembers($members['0'])) && p('message') && e('项目团队成员添加成功');  //添加项目团队成员
r($tester->deleteMembers())           && p('status')  && e('SUCCESS');               //删除项目已有的团队成员
r($tester->copyDeptMembers())         && p('status')  && e('SUCCESS');               //复制部门成员

$tester->closeBrowser();
