#!/usr/bin/env php
<?php

/**

title=测试 programModel::setMenu();
timeout=0
cid=0

- 测试项目集ID为1的菜单设置，返回programID为1属性programID @1
- 测试项目集ID为0的菜单设置，返回programID为0属性programID @0
- 测试项目集ID为999的菜单设置，返回programID为999属性programID @999
- 测试项目集ID为-1的菜单设置，返回programID为-1属性programID @-1
- 测试项目集ID为100000的菜单设置，返回programID为100000属性programID @100000

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1-10');
$program->type->range('program');
$program->name->setFields(array(
    array('field' => 'name1', 'range' => '项目集'),
    array('field' => 'name2', 'range' => '1-10')
));
$program->status->range('doing{5},wait{3},closed{2}');
$program->deleted->range('0');
$program->gen(10);

$programTester = new programTest();

r($programTester->setMenuTest(1)) && p('programID') && e('1'); // 测试项目集ID为1的菜单设置，返回programID为1
r($programTester->setMenuTest(0)) && p('programID') && e('0'); // 测试项目集ID为0的菜单设置，返回programID为0
r($programTester->setMenuTest(999)) && p('programID') && e('999'); // 测试项目集ID为999的菜单设置，返回programID为999
r($programTester->setMenuTest(-1)) && p('programID') && e('-1'); // 测试项目集ID为-1的菜单设置，返回programID为-1
r($programTester->setMenuTest(100000)) && p('programID') && e('100000'); // 测试项目集ID为100000的菜单设置，返回programID为100000