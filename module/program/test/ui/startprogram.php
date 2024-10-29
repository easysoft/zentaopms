#!/usr/bin/env php
<?php

/**

title=开始项目集测试
timeout=0

- 开始项目集，启动成功
 - 测试结果 @开始项目集成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/startprogram.ui.class.php';

$program = zenData('project');
$program->id->range('1-2');
$program->project->range('0');
