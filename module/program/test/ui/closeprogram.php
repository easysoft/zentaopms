#!/usr/bin/env php
<?php

/**

title=关闭项目集测试
timeout=0
cid=0

- 关闭项目集，关闭成功
 - 测试结果 @关闭项目集成功
 - 最终测试状态 @SUCCESS

 */

chdir(__DIR__);
include '../lib/ui/closeprogram.ui.class.php';

$program = zenData('project');
$program->id->range('1-2');
$program->project->range('0');
$program->type->range('program');
$program->name->range('项目集1,项目集2');
$program->acl->range('open');
$program->status->range('doing');
$program->gen(2);

$tester = new createProgramTester();
$tester->login();

r($tester->closeProgram()) && p('message,status') && e('关闭项目集成功,SUCCESS'); //关闭项目集成功
$tester->closeBrowser();
