#!/usr/bin/env php
<?php
/**

title=测试 programModel::getKanbanGroup();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';

$program = zdTable('project');
$program->id->range('1-3');
$program->name->range('1-3')->prefix('项目集');
$program->type->range('program');
$program->path->range('1-3')->prefix(',')->postfix(',');
$program->grade->range('1');
$program->status->range('wait');
$program->openedBy->range('admin,test1');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(3);

zdTable('stakeholder')->gen(0);
zdTable('product')->gen(0);
zdTable('task')->gen(0);
zdTable('projectproduct')->gen(0);
zdTable('productplan')->gen(0);
zdTable('release')->gen(0);
zdTable('team')->gen(0);
zdTable('user')->gen(5);
su('admin');

global $app;
$app->rawModule = 'program';

$programTester = new programTest();
$kanbanGroup   = $programTester->getKanbanGroupTest();

r(count($kanbanGroup['my']))     && p('')       && e('2');       //查看当前用户负责的项目集看板数量
r(count($kanbanGroup['others'])) && p('')       && e('1');       //查看当前用户其他的项目集看板数量
r($kanbanGroup['my'])            && p('0:name') && e('项目集1'); //查看当前用户负责的项目集看板详情
r($kanbanGroup['others'])        && p('0:name') && e('项目集2'); //查看当前用户其他的项目集看板详情
