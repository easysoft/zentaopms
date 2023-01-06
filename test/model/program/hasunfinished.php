#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1-5');
$program->name->range('项目集1,项目集2');
$program->type->range('program');
$program->status->range('wait');
$program->parent->range('0,0,1,1,2');
$program->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(5);

/**

title=测试 programModel::hasUnfinished();
cid=1
pid=1

获取项目集1下未完成的项目和项目集数量 >> 2
获取项目集2下未完成的项目和项目集数量 >> 1

*/

$programTester = new programTest();

r($programTester->hasUnfinishedTest(1)) && p() && e('2'); // 获取项目集1下未完成的项目和项目集数量
r($programTester->hasUnfinishedTest(2)) && p() && e('1'); // 获取项目集2下未完成的项目和项目集数量
