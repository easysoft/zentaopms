#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

$program = zdTable('project');
$program->id->range('1,2');
$program->name->range('项目集1,项目集2');
$program->type->range('program');
$program->budget->range('900000,899900');
$program->path->range('1,2')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(2);

su('admin');

/**

title=测试 programModel::setMenu();
cid=1
pid=1

项目集存在的情况   >> 项目集1
项目集不存在的情况 >> 0

*/

$programTester = new programTest();

r($programTester->setMenuTest(1)) && p() && e('项目集1'); // 项目集存在的情况
r($programTester->setMenuTest(3)) && p() && e('0');       // 项目集不存在的情况
