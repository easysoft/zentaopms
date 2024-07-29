#!/usr/bin/env php
<?php

/**

title=启动和挂起项目测试
timeout=0
cid=73

- 启动项目成功  测试结果 @启动项目成功
- 挂起项目成功  测试结果 @挂起项目成功

*/
chdir(__DIR__);
include '../lib/startproject.ui.class.php';

$tester = new startProjectTester();
$tester->login();

$project = array();

r($tester->startProject($project))   && p('message') && e('启动项目成功');   //启动项目
r($tester->suspendProject($project)) && p('message') && e('挂起项目成功');   //挂起项目

$tester->closeBrowser();
