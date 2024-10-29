#!/usr/bin/env php
<?php

/**

title=关闭项目集测试
timeout=0

- 关闭项目集，关闭成功
 - 测试结果 @关闭项目集成功
 - 最终测试状态 @SUCCESS

 */

chdir(__DIR__);
include '../lib/closeprogram.ui.class.php';

$program = zenData('project');
$program->id->range('1-2');
