#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint{3}');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

zdTable('user')->gen(5);
zdTable('team')->gen(0);
zdTable('product')->gen(0);
zdTable('userview')->gen(0);

su('admin');

/**

title=测试executionModel->updateUserView();
cid=1
pid=1

默认情况下的用户是否有执行的可视权限   >> 2

*/

$execution = new executionTest();

r(strpos($execution->updateUserViewTest(5), ',5,')) && p() && e('2'); // 默认情况下的用户是否有执行的可视权限
