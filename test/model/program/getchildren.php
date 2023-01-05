#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1-3');
$program->name->range('父项目集1,子项目集1,子项目集2');
$program->parent->range('0,1,1');
$program->grade->range('1,2,2');
$program->type->range('program');
$program->path->range('`,1,`,`,1,2,`,`,1,3,`');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(3);

/**

title=测试 programModel:: getChildren();
cid=1
pid=1

通过id查找id=1的子项目集个数 >> 2

*/

$programTester = new programTest();

r($programTester->getChildrenTest(1)) && p() && e('2'); // 通过id查找id=1的子项目集个数
