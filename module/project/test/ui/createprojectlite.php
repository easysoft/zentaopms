#!/usr/bin/env php
<?php

/**

title=创建运营界面项目
timeout=0
cid=23

- 创建项目缺少项目名称
 - 测试结果 @创建项目表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 创建项目计划完成时间置空
 - 测试结果 @创建项目表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 创建有日期的项目
 - 测试结果 @创建项目成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/createprojectlite.ui.class.php';

zendata('project')->gen(0);
$tester = new createProjectLiteTester();
$tester->login();

//设置项目数据
$project = array(
    array('name' => '', 'end' => date('Y-m-d', strtotime('+30 days'))),
    array('name' => '运营看板项目'.time(), 'end' => ''),
    array('name' => '运营看板项目'.time(), 'end' => date('Y-m-d', strtotime('+1 month')), 'PM' => 'admin'),
);

r($tester->checkInput($project['0'])) && p('message,status') && e('创建项目表单页提示信息正确,SUCCESS'); // 创建项目缺少项目名称
r($tester->checkInput($project['1'])) && p('message,status') && e('创建项目表单页提示信息正确,SUCCESS'); // 创建项目计划完成时间置空
r($tester->checkInput($project['2'])) && p('message,status') && e('创建项目成功,SUCCESS');               // 创建项目

$tester->closeBrowser();
