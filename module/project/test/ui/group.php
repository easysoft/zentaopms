#!/usr/bin/env php
<?php

/**

title=创建项目权限分组
timeout=0
cid=1

- 项目创建分组必填提示信息检查
 - 测试结果 @项目创建分组必填提示信息正确
 - 最终测试状态 @SUCCESS
- 创建项目分组成功
 - 测试结果 @项目分组创建成功
 - 最终测试状态 @SUCCESS
- 项目创建分组名称重复时提示信息检查
 - 测试结果 @项目创建分组名称重复时提示信息正确
 - 最终测试状态 @SUCCESS

*/

chdir(__DIR__);
include '../lib/ui/group.ui.class.php';

zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('group')->loadYaml('group')->gen(0);

$tester = new groupTester();
$tester->login();

//设置项目分组数据
$project = array(
    array('groupname' => '', 'groupdesc' => ''),
    array('groupname' => '项目分组1', 'groupdesc' => '一个分组描述哈哈哈222'),
    array('groupname' => '项目分组1', 'groupdesc' => '一个分组描述哈哈哈222'),
);

r($tester->createGroup($project['0'])) && p('message,status') && e('项目创建分组必填提示信息正确,SUCCESS');       // 项目创建分组必填提示信息检查
r($tester->createGroup($project['1'])) && p('message,status') && e('项目分组创建成功,SUCCESS');                   // 创建项目分组成功
r($tester->createGroup($project['2'])) && p('message,status') && e('项目创建分组名称重复时提示信息正确,SUCCESS'); // 项目创建分组名称重复时提示信息检查

$tester->closeBrowser();
