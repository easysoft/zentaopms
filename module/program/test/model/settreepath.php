#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1,10');
$program->name->range('项目集1');
$program->type->range('program');
$program->parent->range('0,1');
$program->path->range('1,10')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(2);

/**

title=测试 programModel::setTreePath();
timeout=0
cid=17710

- 设置之前的项目集的path属性path @,10,
- 设置之后的项目集的path属性path @,1,10,
- 传入0的情况 @0
- 其他项目集的path属性path @0
- 其他项目集的path属性path @0

*/

$programTester = new programModelTest();

$before = $programTester->getByIDTest(10);
$after  = $programTester->setTreePathTest(10);
$error  = $programTester->setTreePathTest(0);

r($before) && p('path', ';') && e(',10,');   // 设置之前的项目集的path
r($after)  && p('path', ';') && e(',1,10,'); // 设置之后的项目集的path
r($error)  && p('')          && e('0');      // 传入0的情况

$before = $programTester->getByIDTest(9);
$after  = $programTester->setTreePathTest(9);

r($before) && p('path', ';') && e('0'); // 其他项目集的path
r($after)  && p('path', ';') && e('0'); // 其他项目集的path