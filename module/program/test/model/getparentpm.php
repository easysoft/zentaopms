#!/usr/bin/env php
<?php

/**

title=测试 programModel::getParentPM();
timeout=0
cid=17691

- 获取父项目集的负责人数量 @4
- 获取父项目集的负责人account第6条的admin属性 @admin
- 获取父项目集的负责人account第7条的test1属性 @test1
- 获取父项目集的负责人account第8条的admin属性 @admin
- 获取父项目集的负责人account第9条的test1属性 @test1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1-10');
$program->name->range('1-10')->prefix('项目集');
$program->type->range('program');
$program->path->range('1-5,`1,6`,`2,7`,`3,8`,`4,9`,`5,10`')->prefix(',')->postfix(',');
$program->grade->range('1{5},2{5}');
$program->parent->range('0{5},1-5');
$program->status->range('wait,doing,suspended,closed');
$program->openedBy->range('admin,test1');
$program->PM->range('admin,test1');
$program->acl->range('private');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->deleted->range('0{15},1{5}');
$program->gen(10);

$programTester = new programModelTest();

$programIdList = array(5, 6, 7, 8, 9);
$parentPM      = $programTester->getParentPMTest($programIdList);

r(count($parentPM)) && p()          && e('4');     // 获取父项目集的负责人数量
r($parentPM)        && p('6:admin') && e('admin'); // 获取父项目集的负责人account
r($parentPM)        && p('7:test1') && e('test1'); // 获取父项目集的负责人account
r($parentPM)        && p('8:admin') && e('admin'); // 获取父项目集的负责人account
r($parentPM)        && p('9:test1') && e('test1'); // 获取父项目集的负责人account
