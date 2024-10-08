#!/usr/bin/env php
<?php

/**

title=编辑瀑布项目阶段测试
timeout=0
cid=3

- 校验阶段名称不能为空
 - 测试结果 @编辑阶段表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 校验计划开始必填
 - 测试结果 @编辑阶段表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 校验计划完成必填
 - 测试结果 @编辑阶段表单页提示信息正确
 - 最终测试状态 @SUCCESS
 - 编辑需求阶段最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/editstage.ui.class.php';

zendata('project')->loadYaml('execution', false, 2)->gen(10);
zendata('projectspec')->gen(0);
$tester = new editStageTester();
$tester->login();

$waterfall = array(
    array('name' => '', 'begin' => '2020-11-02', 'end' => '2020-11-09'),
    array('name' => '需求的阶段a1', 'begin' => '', 'end' => '2020-11-09'),
    array('name' => '需求的阶段a1', 'begin' => '2020-11-02', 'end' => ''),
    array('name' => '需求的阶段a1', 'begin' => '2020-11-11', 'end' => '2020-11-16'),
);

r($tester->checkInput($waterfall['0'])) && p('message,status') && e('编辑阶段表单页提示信息正确, SUCCESS'); //校验阶段名称不能为空
r($tester->checkInput($waterfall['1'])) && p('message,status') && e('编辑阶段表单页提示信息正确, SUCCESS'); //校验计划开始必填
r($tester->checkInput($waterfall['2'])) && p('message,status') && e('编辑阶段表单页提示信息正确, SUCCESS'); //校验计划完成必填
r($tester->checkInput($waterfall['3'])) && p('status') && e('SUCCESS');                                     //编辑需求阶段

$tester->closeBrowser();
