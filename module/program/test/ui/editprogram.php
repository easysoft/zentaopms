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
