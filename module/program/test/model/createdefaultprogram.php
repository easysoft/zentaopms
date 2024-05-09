#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

/**

title=测试 programModel::createDefaultProgram();
cid=1
pid=1

创建默认项目集 >> 1

*/

$programTester = new programTest();

$result = $programTester->createDefaultProgramTest();

r($result) && p('') && e('1'); // 创建默认项目集
