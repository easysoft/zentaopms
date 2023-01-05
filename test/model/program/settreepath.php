#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1,10');
$program->name->range('项目集1');
$program->type->range('program');
$program->parent->range('[],[1]');
$program->path->range('1,10')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(2);

/**

title=测试 programModel::setTreePath();
cid=1
pid=1

设置之前的项目集的path >> ,10,
设置之后的项目集的path >> ,1,10,

*/

$programTester = new programTest();

$before = $programTester->getByIDTest(10);
$after  = $programTester->setTreePathTest(10);

r($before) && p('path') && e(',10,');   // 设置之前的项目集的path
r($after)  && p('path') && e(',1,10,'); // 设置之后的项目集的path
