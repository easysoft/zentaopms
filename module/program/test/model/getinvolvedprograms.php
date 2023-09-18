#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';
zdTable('user')->gen(5);
su('admin');

$program = zdTable('project');
$program->id->range('1-3');
$program->name->range('1-3')->prefix('项目集');
$program->type->range('program');
$program->path->range('1-3')->prefix(',')->postfix(',');
$program->grade->range('1');
$program->openedBy->range('admin,test1');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(3);

zdTable('stakeholder')->gen(0);
zdTable('team')->gen(0);
zdTable('product')->gen(0);

/**

title=测试 programModel::getInvolvedPrograms();
timeout=0
cid=1

*/

$programTester = new programTest();
$adminPrograms = $programTester->getInvolvedProgramsTest('admin');
$test1Programs = $programTester->getInvolvedProgramsTest('test1');

r(count($adminPrograms)) && p()    && e('2'); // 获取admin参与的项目集数量
r(count($test1Programs)) && p()    && e('1'); // 获取test1参与的项目集数量
r($adminPrograms)        && p('1') && e('1'); // 获取admin参与的项目集ID
r($test1Programs)        && p('2') && e('2'); // 获取test1参与的项目集ID
