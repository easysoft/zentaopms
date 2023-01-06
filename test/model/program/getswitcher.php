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

title=测试 programModel::saveState();
cid=1
pid=1

查看传入空项目集返回的下拉菜单 >> 所有项目集
查看传入id=1的项目集返回的下拉菜单 >> 父项目集1

*/

$programTester = new programTest();
$switcher1     = $programTester->getSwitcherTest(0);
$switcher2     = $programTester->getSwitcherTest(1);

r(strip_tags($switcher1)) && p() && e('所有项目集'); // 查看传入空项目集返回的下拉菜单
r(strip_tags($switcher2)) && p() && e('父项目集1');  // 查看传入id=1的项目集返回的下拉菜单
