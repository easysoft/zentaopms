#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::createDefaultProgram();
cid=1
pid=1

创建默认项目集 >> 1

*/

$programTester = new programTest();

$result = $programTester->createDefaultProgramTest();

r($result) && p('') && e('1'); // 创建默认项目集
