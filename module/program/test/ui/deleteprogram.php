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
