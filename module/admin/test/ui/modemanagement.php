#!/usr/bin/env php
<?php

/**

title=切换管理模式测试
timeout=0

- 切换轻量级模式，切换成功
 - 测试结果 @切换轻量级模式成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/modemanagement.ui.class.php';

$program = zenData('project');
$program->id->range('1-3');
$program->type->range('program');
$program->name->range('项目集A,项目集B,项目集C');
$program->begin->range('2025-01-01');
$program->end->range('2025-12-31');
$program->status->range('doing');
$program->gen(3);

$tester = new modemanagementTester();
$tester->login();

$programName = new stdClass();
$programName->secProgram = '项目集B';

r($tester->modeManagement($programName)) && p('message,status') && e('切换轻量级模式成功,SUCCESS'); //切换轻量级模式成功
