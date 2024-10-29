#!/usr/bin/env php
<?php

/**

title=删除项目集测试
timeout=0

- 删除项目集，删除成功
 - 测试结果 @删除项目集成功
 - 最终测试状态 @SUCCESS

 */

chdir(__DIR__);
include '../lib/deleteprogram.ui.class.php';

$program = zenData('project');
$program->id->range('1-2');
$program->project->range('0');
$program->type->range('program');
$program->name->range('项目集1,项目集2');
$program->acl->range('open');
$program->gen(2);

$tester = new createProgramTester();
$tester->login();

$programName = new stdClass();
$programName->name = '项目集1';

r($tester->deleteProgram($programName)) && p('message,status') && e ('删除项目集成功，SUCCESS'); //删除项目集成功
