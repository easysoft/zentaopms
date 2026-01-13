#!/usr/bin/env php
<?php

/**

title=测试 programModel::getParentPairs();
cid=17690
pid=1

- 获取所有父项目集的数量 @5
- 获取瀑布类型父项目集的数量 @1
- 获取ID为1的父项目集的名称属性1 @/项目集1
- 获取父项目集的名称显示根目录 @0
- 获取ID为1的父项目集的名称不显示根目录 @项目集1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1-10');
$program->name->setFields(array(
    array('field' => 'name1', 'range' => '项目集{5},项目{5}'),
    array('field' => 'name2', 'range' => '1-5'),
));
$program->type->range('program{5},project{5}');
$program->path->range('1-5,`1,6`,`2,7`,`3,8`,`4,9`,`5,10`')->prefix(',')->postfix(',');
$program->grade->range('1{5},2{5}');
$program->parent->range('0{5},1-5');
$program->status->range('wait,doing,suspended,closed');
$program->model->range('[]{5},scrum{2},waterfall{2},kanban');
$program->openedBy->range('admin,test1');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->deleted->range('0{15},1{5}');
$program->gen(10);

$programTester = new programTest();

$program1 = $programTester->getParentPairsTest();
$program2 = $programTester->getParentPairsTest('waterfall');
$program3 = $programTester->getParentPairsTest('', 'noclosed', false);

r(count($program1))       && p()    && e('5');        // 获取所有父项目集的数量
r(count($program2))       && p()    && e('1');        // 获取瀑布类型父项目集的数量
r($program1)              && p('1') && e('/项目集1'); // 获取ID为1的父项目集的名称
r(key($program1))         && p('0') && e('0');        // 获取父项目集的名称显示根目录
r(array_shift($program3)) && p()    && e('项目集1');  // 获取ID为1的父项目集的名称不显示根目录
