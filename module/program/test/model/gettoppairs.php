#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(5);
su('admin');

$program = zenData('project');
$program->id->range('1-5');
$program->name->range('1-5')->prefix('项目集');
$program->type->range('program');
$program->grade->range('1{4},2');
$program->status->range('wait,closed,wait{3}');
$program->parent->range('0{4},1');
$program->path->range('1,2,3,4,`1,5`')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->deleted->range('0{3},1,0');
$program->gen(5);

/**

title=测试 programModel::getTopPairs();
timeout=0
cid=17703

- 获取系统中有权限的顶级项目集数量 @3
- 获取系统中有权限的未关闭的顶级项目集数量 @2
- 获取系统中有权限的包括已删除的顶级项目集数量 @4
- 获取系统中所有顶级项目集数量 @3
- 获取系统中所有未关闭的顶级项目集数量 @2
- 获取系统中所有包括已删除的顶级项目集数量 @4

*/

$modeList = array('', 'noclosed', 'withDeleted');

global $tester;
$tester->loadModel('program');
r(count($tester->program->getTopPairs()))                    && p() && e('3'); // 获取系统中有权限的顶级项目集数量
r(count($tester->program->getTopPairs('noclosed')))          && p() && e('2'); // 获取系统中有权限的未关闭的顶级项目集数量
r(count($tester->program->getTopPairs('withDeleted')))       && p() && e('4'); // 获取系统中有权限的包括已删除的顶级项目集数量
r(count($tester->program->getTopPairs('', true)))            && p() && e('3'); // 获取系统中所有顶级项目集数量
r(count($tester->program->getTopPairs('noclosed', true)))    && p() && e('2'); // 获取系统中所有未关闭的顶级项目集数量
r(count($tester->program->getTopPairs('withDeleted', true))) && p() && e('4'); // 获取系统中所有包括已删除的顶级项目集数量
