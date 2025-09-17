#!/usr/bin/env php
<?php

/**

title=启动和挂起项目测试
timeout=0
cid=73

- 启动项目
 - 测试结果 @启动项目成功
 - 最终测试状态 @SUCCESS
- 挂起项目
 - 测试结果 @挂起项目成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/startproject.ui.class.php';

zendata('project')->loadYaml('project', false, 1)->gen(1);

$tester = new startProjectTester();
$tester->login();

r($tester->startProject())   && p('message,status') && e('启动项目成功,SUCCESS');   //启动项目
r($tester->suspendProject()) && p('message,status') && e('挂起项目成功,SUCCESS');   //挂起项目

$tester->closeBrowser();
