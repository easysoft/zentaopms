#!/usr/bin/env php
<?php
chdir(__DIR__);         
include '../lib/createbug.ui.class.php'; 
$tester = new createBugTester();

$bug = array();
$project = array();
$project['productID'] = 1;
$project['branch']    = 0;
$project['extra']     = 'moduleID=0';

r($tester->createDefaultBug($project, $bug)) && p('message,status') && e('bug表单项校验成功,SUCCESS'); //验证bug表单页必填项校验

$bug['title']       = 'bug' . time();
$bug['openedBuild'] = array('multiPicker' => '主干');
$bug['assignedTo']  = 'admin';
$bug['steps']       = 'steps';
r($tester->createDefaultBug($project, $bug)) && p('message,status') && e('bug表单项提交成功,SUCCESS'); //创建bug创建成功场景

$bug['title']    = 'bug' . time();
$bug['deadline'] = array('datePicker' => '2025-05-05');
$bug['type']     = '设计缺陷';
$bug['severity'] = 1;
$bug['pri']      = 1;
r($tester->createDefaultBug($project, $bug)) && p('message,status') && e('bug表单项提交成功,SUCCESS'); //创建bug输入非必填项后提交表单
