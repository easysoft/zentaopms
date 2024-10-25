#!/usr/bin/env php
<?php

/**

title=创建项目集测试
timeout=0

- 编辑项目集名称，编辑成功
 - 测试结果 @编辑项目集成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/editprogram.ui.class.php';

$program = zenData('project');
$program->id->range('1-2');
$program->project->range('0');
$program->type->range('program');
$program->name->range('项目集1,项目集2');
$program->acl->range('open');
$program->gen(2);

$tester = new createProgramTester();
$tester->login();

$editName = new stdClass();
$editName->name = '编辑过的项目集';

r($tester->editProgram($editName)) && p('message,status') && e ('编辑项目集成功，SUCCESS');
