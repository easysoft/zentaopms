#!/usr/bin/env php
<?php

/**

title=创建敏捷项目测试
timeout=0
cid=73

- 缺少产品名称，创建失败
 -  测试结果 @创建产品表单页提示信息正确
 -  最终测试状态 @SUCCESS
- 使用默认选项创建产品 最终测试状态 @SUCCESS
- 创建重复名称的产品 测试结果 @创建产品表单页提示信息正确
- 创建正常产品后的跳转链接检查
 - 属性module @product
 - 属性method @browse
- 创建正常产品成功 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createscrum.ui.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(10);
$tester = new createScrumTester();
$tester->login();

$scrum = array();
$scrum['null'] = '';
$scrum['hasproduct'] = '一个产品型项目'.time();
$scrum['noproduct'] = '一个项目型项目'.time();
$scrum['end'] = date("Y-m-d",strtotime("+1 month"));
$scrum['PM'] = 'admin';

r($tester->createScrum($scrum['null']))       && p('message')       && e('创建敏捷项目表单页提示信息正确');    // 缺少项目名称，创建失败
r($tester->createScrum($scrum['hasproduct'])) && p('status')        && e('SUCCESS');                           // 创建产品型项目
r($tester->createScrum($scrum['hasproduct'])) && p('message')       && e('创建敏捷项目表单页提示信息正确');    // 创建重复名称的产品
r($tester->checkLocating($scrum) )            && p('module,method') && e('project,browse');                    // 创建项目型敏捷项目后的跳转链接检查

$tester->closeBrowser();
