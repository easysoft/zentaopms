#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

su('admin');
/**

title=测试 executionModel::getSwitcher();
cid=1
pid=1

查看传入空返回的下拉菜单                        >> 执行
查看传入id=1, 请求all方法的执行返回的下拉菜单   >> 0
查看传入id=1，请求tasks方法的执行返回的下拉菜单 >> 项目集1

*/
$methods = array('all', 'task');

$executionTester = new executionTest();
$switcher1       = $executionTester->getSwitcherTest(0);
$switcher2       = $executionTester->getSwitcherTest(1, $methods[0]);
$switcher3       = $executionTester->getSwitcherTest(1, $methods[1]);

r(strip_tags($switcher1)) && p() && e('执行');     // 查看传入空返回的下拉菜单
r(strip_tags($switcher2)) && p() && e('0');        // 查看传入id=1, 请求all方法的执行返回的下拉菜单
r(strip_tags($switcher3)) && p() && e('项目集1');  // 查看传入id=1，请求tasks方法的执行返回的下拉菜单
