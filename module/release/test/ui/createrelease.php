#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=创建发布页面字段检查
timeout=0
cid=80

- 状态选择未发布，不显示实际发布日期
 -  最终测试状态 @SUCCESS
- 状态选择已发布，显示实际发布日期 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createstory.ui.class.php';

$tester = new createStoryTester();
$tester->login();
