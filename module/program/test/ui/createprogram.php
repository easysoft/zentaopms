#!/usr/bin/env php
<?php

/**

title=创建项目集测试
timeout=0

- 缺少项目集名称，创建失败
 - 测试结果 @创建项目集表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 缺少项目集名称，创建失败
 - 测试结果 @创建项目集表单页提示信息正确
 - 最终测试状态 @SUCCESS
- 创建一个公开项目集，创建成功
 - 测试结果 @创建项目集成功
 - 最终测试状态 @SUCCESS
- 创建一个私有项目集，创建成功
 - 测试结果 @创建私有项目集成功
 - 最终测试状态 @SUCCESS
- 添加一个子项目集，添加成功
 - 测试结果 @添加子项目集成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createprogram.ui.class.php';

zendata('project')->loadYaml('program', false, 2)->gen(10);
$tester = new createProgramTester();
$tester->login();

$programs = array();
$programs['null']    = '';
$programs['repeat']  = '项目集1';
$programs['open']    = '公开项目集';
$programs['private'] = '私有项目集';
$programs['edited']  = '编辑过的项目集';

$childProgram = new stdClass();
$childProgram->name = '子项目集A';

$whitelist = array('admin');

r($tester->createDefault($programs['null']))                && p('message,status') && e('创建项目集表单页提示信息正确,SUCCESS'); // 缺少项目集名称，创建失败
r($tester->createDefault($programs['repeat']))              && p('message,status') && e('创建项目集表单页提示信息正确,SUCCESS'); // 缺少项目集名称，创建失败
r($tester->createDefault($programs['open']))                && p('message,status') && e('创建项目集成功,SUCCESS');               // 创建一个公开项目集，创建成功
r($tester->createPrivate($programs['private'], $whitelist)) && p('message,status') && e('创建私有项目集成功,SUCCESS');           // 创建一个私有项目集，创建成功

r($tester->addChildProgram($programs['open'], $childProgram))                && p('message,status') && e('添加子项目集成功，SUCCESS');   //添加子项目集，添加成功
