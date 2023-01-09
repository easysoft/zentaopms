#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1-10');
$program->name->range('1-10')->prefix('项目集');
$program->type->range('program');
$program->path->range('1-5,`1,6`,`2,7`,`3,8`,`4,9`,`5,10`')->prefix(',')->postfix(',');
$program->grade->range('1{5},2{5}');
$program->parent->range('0{5},1-5');
$program->status->range('wait,doing,suspended,closed');
$program->openedBy->range('admin,test1');
$program->pm->range('admin,test1');
$program->acl->range('private');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->deleted->range('0{15},1{5}');
$program->gen(10);

/**

title=测试 programModel::getParentPM();
cid=1
pid=1

获取父项目集的负责人数量 >> 2
获取父项目集的负责人account >> admin
获取父项目集的负责人account >> test1

*/

$programTester = new programTest();

$programIdList = array(5, 6, 7);
$parentPM      = $programTester->getParentPMTest($programIdList);

r(count($parentPM)) && p()          && e('2');     // 获取父项目集的负责人数量
r($parentPM)        && p('6:admin') && e('admin'); // 获取父项目集的负责人account
r($parentPM)        && p('7:test1') && e('test1'); // 获取父项目集的负责人account
