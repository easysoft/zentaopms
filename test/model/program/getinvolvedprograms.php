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
$program->openedBy->range('admin,test1');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(3);

/**

title=测试 programModel::getInvolvedPrograms();
cid=1
pid=1

获取admin参与的项目集数量 >> 2
获取test1参与的项目集数量 >> 1
获取admin参与的项目集ID >> 1
获取test1参与的项目集ID >> 2

*/

$programTester = new programTest();
$adminPrograms = $programTester->getInvolvedProgramsTest('admin');
$test1Programs = $programTester->getInvolvedProgramsTest('test1');

r(count($adminPrograms)) && p()    && e('2'); // 获取admin参与的项目集数量
r(count($test1Programs)) && p()    && e('1'); // 获取test1参与的项目集数量
r($adminPrograms)        && p('1') && e('1'); // 获取admin参与的项目集ID
r($test1Programs)        && p('2') && e('2'); // 获取test1参与的项目集ID
