#!/usr/bin/env php
<?php

/**

title=创建看板项目
timeout=0
cid=73

- 创建看板项目缺少项目名称
 - 测试结果 @创建看板项目表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 创建看板项目计划完成时间置空
 - 测试结果 @创建看板项目表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 创建有日期的产品型看板项目最终测试状态 @SUCCESS
- 创建一个长期的产品型看板项目最终测试状态 @SUCCESS
- 创建有日期的项目型看板项目最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createkanban.ui.class.php';

$tester = new createKanbanTester();
$tester->login();

//设置看板项目数据
$kanban = array(
    array('name' => '', 'end' => date('Y-m-d', strtotime('+30 days'))),
    array('name' => '一个产品型看板项目'.time(), 'end' => ''),
    array('type' => 1, 'name' => '一个产品型看板项目'.time(), 'end' => date('Y-m-d', strtotime('+1 month')), 'PM' => 'admin'),
    array('type' => 1, 'name' => '一个长期的产品型看板项目'.time(), 'PM' => 'admin'),
    array('type' => 0, 'name' => '一个项目型看板项目'.time(), 'end' => date('Y-m-d', strtotime('+1 month')), 'PM' => 'admin'),
);

r($tester->checkInput($kanban['0'])) && p('message,status') && e('创建看板项目表单页提示信息正确,SUCCESS');   // 创建看板项目缺少项目名称
r($tester->checkInput($kanban['1'])) && p('message,status') && e('创建看板项目表单页提示信息正确,SUCCESS');   // 创建看板项目计划完成时间置空
r($tester->checkInput($kanban['2'])) && p('status')  && e('SUCCESS');                                         // 创建有日期的产品型看板项目
r($tester->checkInput($kanban['3'])) && p('status')  && e('SUCCESS');                                         // 创建一个长期的产品型看板项目
