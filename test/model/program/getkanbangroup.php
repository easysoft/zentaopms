#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1-3');
$program->name->range('1-3')->prefix('项目集');
$program->type->range('program');
$program->path->range('1-3')->prefix(',')->postfix(',');
$program->grade->range('1');
$program->status->range('wait');
$program->openedBy->range('admin,test1');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(3);

/**

title=测试 programModel::getKanbanGroup();
cid=1
pid=1

查看当前用户负责的项目集看板数量 >> 2
查看当前用户其他的项目集看板数量 >> 1
查看当前用户其他的项目集看板详情 >> 项目集2

*/

$programTester = new programTest();

$kanbanGroup = $programTester->getKanbanGroupTest();

r(count($kanbanGroup['my']))     && p('')       && e('2');       //查看当前用户负责的项目集看板数量
r(count($kanbanGroup['others'])) && p('')       && e('1');       //查看当前用户其他的项目集看板数量
r($kanbanGroup['others'])        && p('0:name') && e('项目集2'); //查看当前用户其他的项目集看板详情
