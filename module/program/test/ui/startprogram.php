#!/usr/bin/env php
<?php

/**

title=创建项目集测试
timeout=0

- 开始项目集，启动成功
 - 测试结果 @开始项目集成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/startprogram.ui.class.php';

$program = zenData('project');
$program->id->range('1-2');
r($tester->startProgram($programName)) && p('message,status') && e ('开始项目集成功，SUCCESS'); //开始项目集成功
