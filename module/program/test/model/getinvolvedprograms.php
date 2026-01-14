#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=测试 programModel::getInvolvedPrograms();
timeout=0
cid=17684

- 获取admin参与的项目集数量 @2
- 获取test1参与的项目集数量 @1
- 获取test1参与的项目集数量 @0
- 获取admin参与的项目集ID属性1 @1
- 获取test1参与的项目集ID属性2 @2
- 获取test1参与的项目集ID属性2 @0

*/

zenData('user')->gen(5);
su('admin');

$program = zenData('project');
$program->id->range('1-3');
$program->name->range('1-3')->prefix('项目集');
$program->type->range('program');
$program->path->range('1-3')->prefix(',')->postfix(',');
$program->grade->range('1');
$program->openedBy->range('admin,test1');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(3);

zenData('stakeholder')->gen(0);
zenData('team')->gen(0);
zenData('product')->gen(0);

$programTester = new programModelTest();
$adminPrograms = $programTester->getInvolvedProgramsTest('admin');
$test1Programs = $programTester->getInvolvedProgramsTest('test1');
$user1Programs = $programTester->getInvolvedProgramsTest('user1');

r(count($adminPrograms)) && p()    && e('2'); // 获取admin参与的项目集数量
r(count($test1Programs)) && p()    && e('1'); // 获取test1参与的项目集数量
r(count($user1Programs)) && p()    && e('0'); // 获取test1参与的项目集数量
r($adminPrograms)        && p('1') && e('1'); // 获取admin参与的项目集ID
r($test1Programs)        && p('2') && e('2'); // 获取test1参与的项目集ID
r($user1Programs)        && p('2') && e('0'); // 获取test1参与的项目集ID
