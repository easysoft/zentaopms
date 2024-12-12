#!/usr/bin/env php
<?php

/**

title=添加待办测试
timeout=0

- 添加一个待办，添加成功
 - 测试结果 @添加待办成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/checktodotabs.ui.class.php';

$todo = zenData('todo');
$todo->id->range('1-4');
$todo->account->range('admin');
