#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1-5');
$program->name->range('项目集1,项目集2');
$program->type->range('program');
$program->grade->range('1,1,0{3}');
$program->status->range('wait,closed,wait{3}');
$program->parent->range('0,0,1,1,2');
$program->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(5);

/**

title=测试 programModel::getTopPairs();
cid=1
pid=1

获取系统中所有顶级项目集数量         >> 2
获取系统中所有未关闭的顶级项目集数量 >> 1
获取系统中所有顶级项目集数量         >> 2

*/

$programTester = new programTest();

r(count($programTester->getTopPairsTest()))               && p() && e('2'); // 获取系统中所有顶级项目集数量
r(count($programTester->getTopPairsTest('', 'noclosed'))) && p() && e('1'); // 获取系统中所有未关闭的顶级项目集数量
r(count($programTester->getTopPairsTest('', '', true)))   && p() && e('2'); // 获取系统中所有顶级项目集数量
