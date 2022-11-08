#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
$db->switchDB();

/**

title=测试 programModel::createDefaultProgram();
cid=1
pid=1

创建默认项目集并返回ID >> 751

*/

$test = new programTest();

$result = $test->createDefaultProgramTest();

r($result) && p('') && e('751'); // 创建默认项目集并返回ID

$db->restoreDB();