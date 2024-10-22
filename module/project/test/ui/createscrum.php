#!/usr/bin/env php
<?php

/**

title=创建敏捷项目测试
timeout=0
cid=73

- 缺少项目名称，检查提示信息
 - 测试结果 @创建敏捷项目表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 计划完成时间置空，检查提示信息
 - 测试结果 @创建敏捷项目表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 创建有日期的产品型项目最终测试状态 @SUCCESS
- 创建一个长期的产品型项目，检查提示信息最终测试状态 @SUCCESS
- 创建有日期的项目型项目最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createscrum.ui.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(10);
$tester = new createScrumTester();
$tester->login();

//设置敏捷项目数据
$scrum = array(
    array('name' => '', 'end' => date('Y-m-d', strtotime('+30 days'))),
    array('name' => '一个产品型项目'.time(), 'end' => ''),
    array('type' => 1, 'name' => '一个产品型项目'.time(), 'end' => date('Y-m-d', strtotime('+1 month')), 'PM' => 'admin'),
    array('type' => 1, 'name' => '一个长期的产品型项目'.time(), 'PM' => 'admin'),
    array('type' => 0, 'name' => '一个项目型项目'.time(), 'end' => date('Y-m-d', strtotime('+1 month')), 'PM' => 'admin'),
);

r($tester->checkInput($scrum['0'])) && p('message,status') && e('创建敏捷项目表单页提示信息正确,SUCCESS');   // 缺少项目名称，检查提示信息
r($tester->checkInput($scrum['1'])) && p('message,status') && e('创建敏捷项目表单页提示信息正确,SUCCESS');   // 计划完成时间置空，检查提示信息
r($tester->checkInput($scrum['2'])) && p('status')  && e('SUCCESS');                                         // 创建有日期的产品型项目
r($tester->checkInput($scrum['3'])) && p('status')  && e('SUCCESS');                                         // 创建一个长期的产品型项目，检查提示信息
r($tester->checkInput($scrum['4'])) && p('status')  && e('SUCCESS');                                         // 创建有日期的项目型项目

$tester->closeBrowser();
