#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

$program = zdTable('project');
$program->id->range('1-5');
$program->name->range('项目集1,项目集2,项目1,项目2,项目3');
$program->type->range('program{2},project{3}');
$program->status->range('doing{3},closed,doing');
$program->parent->range('0,0,1,1,2');
$program->grade->range('1{2},2{3}');
$program->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(5);

/**

title=测试 programModel::processNode();
cid=1
pid=1

更新项目集层级和路径 >> 1

*/

global $tester;
$tester->loadModel('program');

r($tester->program->processNode(1, 2, 1, 1)) && p() && e('1'); // 更新项目集层级和路径
