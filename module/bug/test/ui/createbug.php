#!/usr/bin/env php
<?php
chdir(__DIR__);         
include '../lib/createbug.ui.class.php'; 

/**

title=创建bug测试
timeout=0
cid=1

- 验证bug表单页必填项校验
 - 测试结果 @bug表单必填项校验成功
 - 最终测试状态 @SUCCESS
- 创建bug
 - 测试结果 @创建bug成功
 - 最终测试状态 @SUCCESS
- 创建bug输入非必填项后提交表单
 - 测试结果 @创建bug成功
 - 最终测试状态 @SUCCESS

*/
zenData('product')->loadYaml('product')->gen(1);
$tester = new createBugTester();

$bug = array();
$project = array();
$project['productID'] = 1;
$project['branch']    = 0;
$project['extra']     = 'moduleID=0';

r($tester->createDefaultBug($project, $bug)) && p('message,status') && e('bug表单必填项校验成功,SUCCESS'); //验证bug表单页必填项校验

$bug['title']       = 'bug' . time();
$bug['openedBuild'] = array('multiPicker' => '主干');
$bug['assignedTo']  = 'admin';
$bug['steps']       = 'steps';
r($tester->createDefaultBug($project, $bug)) && p('message,status') && e('创建bug成功,SUCCESS'); //创建bug

$bug['title']    = 'bug' . time();
$bug['deadline'] = array('datePicker' => '2025-05-05');
$bug['type']     = '设计缺陷';
$bug['severity'] = 1;
$bug['pri']      = 1;
r($tester->createDefaultBug($project, $bug)) && p('message,status') && e('创建bug成功,SUCCESS'); //创建bug输入非必填项后提交表单