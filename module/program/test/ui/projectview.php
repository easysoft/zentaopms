#!/usr/bin/env php
<?php

/**
title=项目视角下添加项目测试
timeout=0

- 添加项目，选择所属项目集后保存
 - 测试结果 @项目保存成功且显示在项目集列表。
 - 最终测试状态 @SUCCESS
*/
chdir(__DIR__);
include '../lib/projectview.ui.class.php';

$tester = new createProgramTester();
$tester->login();

$programs = new stdClass();
$programs->program = '项目集3';

$projects = new stdClass();
$projects->programProject = '项目A';

r($tester->createProgramProject($programs, $projects)) && p('message,status') && e('项目视角下创建项目成功，SUCCESS'); //项目视角下创建项目成功
