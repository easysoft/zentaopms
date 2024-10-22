#!/usr/bin/env php
<?php

/**

title=敏捷项目下创建迭代
timeout=0
cid=1

- 执行名称为空时的提示校验
 - 测试结果 @创建迭代表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 计划完成时间为空时的提示校验
 - 测试结果 @创建迭代表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 添加迭代成功最终测试状态 @SUCCESS

 */

chdir(__DIR__);
include '../lib/createsprint.ui.class.php';

zendata('project')->loadYaml('project', false, 1)->gen(1);
$tester = new createSprintTester();
$tester->login();

//设置敏捷项目执行数据
$sprint = array(
    array('project' => '敏捷项目1', 'name' => '', 'end' => date('Y-m-d', strtotime('+5 days'))),
    array('project' => '敏捷项目1', 'name' => '一个敏捷迭代' . time(), 'end' => ''),
    array('project' => '敏捷项目1', 'name' => '一个敏捷迭代' . time(), 'end' => date('Y-m-d', strtotime('+5 days'))),
);

r($tester->checkInput($sprint['0'])) && p('message,status') && e('创建迭代表单页提示信息正确,SUCCESS');  //执行名称为空时的提示校验
r($tester->checkInput($sprint['1'])) && p('message,status') && e('创建迭代表单页提示信息正确,SUCCESS');  //计划完成时间为空时的提示校验
r($tester->checkInput($sprint['2'])) && p('status')  && e('SUCCESS');                                    //添加迭代成功

$tester->closeBrowser();
