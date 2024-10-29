#!/usr/bin/env php
<?php

/**

title=激活项目集测试
timeout=0

- 激活项目集，激活成功
 - 测试结果 @激活项目集成功
 - 最终测试状态 @SUCCESS

 */

chdir(__DIR__);
include '../lib/activateprogram.ui.class.php';

$program = zenData('project');
$program->id->range('1-2');
