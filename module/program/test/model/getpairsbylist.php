#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1-10');
$program->name->range('1-10')->prefix('项目集');
$program->type->range('program');
$program->path->range('1-10')->prefix(',')->postfix(',');
$program->grade->range('1');
$program->status->range('wait,doing,suspended,closed');
$program->openedBy->range('admin,test1');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(10);

/**

title=测试 programModel::getPairsByList();
cid=17689
pid=1

通过字符串'1'获取项目集名称 >> 项目集1
通过字符串'1,2,3'获取项目集名称 >> 项目集1,项目集2,项目集3
通过数组array(1)获取项目集名称 >> 项目集1
通过数组array(1,2,3)获取项目集名称 >> 项目集1,项目集2,项目集3
通过id为空获取项目集名称 >> 0
通过id=0获取项目集名称 >> 0
通过id=11获取项目集名称 >> 0

*/

$programTester = new programTest();
$programIdList = array(array(1), array(1,2,3), '', '0', '11');

r($programTester->getPairsByListTest($programIdList[0])) && p('1')     && e('项目集1');                 // 通过字符串'1'获取项目集名称
r($programTester->getPairsByListTest($programIdList[1])) && p('1,2,3') && e('项目集1,项目集2,项目集3'); // 通过字符串'1,2,3'获取项目集名称
r($programTester->getPairsByListTest($programIdList[2])) && p('')      && e('0');                       // 通过id为空获取项目集名称
r($programTester->getPairsByListTest($programIdList[3])) && p('')      && e('0');                       // 通过id=0获取项目集名称
r($programTester->getPairsByListTest($programIdList[4])) && p('')      && e('0');                       // 通过id=11获取项目集名称
