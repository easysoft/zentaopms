#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

$program = zdTable('project');
$program->id->range('1,2');
$program->name->range('父项目集1,父项目集2');
$program->type->range('program');
$program->budget->range('900000,899900');
$program->path->range('1,2')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(2);

su('admin');
/**

title=测试 programModel::buildOperateMenu();
cid=1
pid=1

项目集不存在 >> 0
项目集存在   >> div

*/

$programTester = new programTest();
$menus1        = $programTester->buildOperateMenuTest(0);
$menus2        = $programTester->buildOperateMenuTest(1);

r($menus1) && p() && e('0');                 // 项目集不存在
r(substr($menus2, 1, 3)) && p() && e('div'); // 项目集存在
