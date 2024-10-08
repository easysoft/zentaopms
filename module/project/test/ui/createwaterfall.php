#!/usr/bin/env php
<?php

/**

title=创建瀑布项目测试
timeout=0
cid=73

- 校验项目名称不能为空
 - 测试结果 @创建瀑布项目表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 校验计划完成时间不能为空
 - 测试结果 @创建瀑布项目表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 创建长期瀑布项目最终测试状态 @SUCCESS
- 校验项目名称不能重复测试结果 @创建瀑布项目表单页提示信息正确
- 创建指定计划完成的瀑布项目最终测试状态 @SUCCESS
- 创建指定计划完成的项目型瀑布项目最终测试状态 @SUCCESS
- 创建瀑布项目后的跳转
 - 属性module @programplan
 - 属性method @create

*/
chdir(__DIR__);
include '../lib/createwaterfall.ui.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(10);
zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('projectproduct')->gen(0);
$tester = new createWaterfallTester();
$tester->login();

$waterfall = array(
    array('name'   => '', 'longTime' => 'longTime', 'PM' => 'admin'),
    array('name'   => '瀑布项目h01', 'end' => ''),
    array('parent' => '项目集1', 'name' => '瀑布项目h01', 'type' => 1, 'longTime' => 'longTime'),
    array('name'   => '瀑布项目h01', 'type' => 1, 'longTime' => 'longTime'),
    array('name'   => '瀑布项目h02', 'type' => 1, 'end' => date('Y-m-d', strtotime('+30 days')), 'PM' => 'admin'),
    array('name'   => '项目型瀑布项目h01', 'type' => 0, 'end' => date('Y-m-d', strtotime('+30 days')), 'PM' => 'admin'),
    array('name'   => '瀑布项目h03', 'end' => date('Y-m-d', strtotime('+30 days')), 'PM' => 'admin'),
);

r($tester->createDefault($waterfall['0'])) && p('message,status') && e('创建瀑布项目表单页提示信息正确, SUCCESS'); // 校验项目名称不能为空
r($tester->createDefault($waterfall['1'])) && p('message,status') && e('创建瀑布项目表单页提示信息正确, SUCCESS'); // 校验计划完成时间不能为空
r($tester->createDefault($waterfall['2'])) && p('status')         && e('SUCCESS');                                 // 创建长期瀑布项目
r($tester->createDefault($waterfall['3'])) && p('message')        && e('创建瀑布项目表单页提示信息正确');          // 校验项目名称不能重复
r($tester->createDefault($waterfall['4'])) && p('status')         && e('SUCCESS');                                 // 创建指定计划完成的瀑布项目
r($tester->createDefault($waterfall['5'])) && p('status')         && e('SUCCESS');                                 // 创建指定计划完成的项目型瀑布项目
r($tester->checkLocating($waterfall['6'])) && p('module,method')  && e('programplan, create');                     // 创建瀑布项目后的跳转

$tester->closeBrowser();
