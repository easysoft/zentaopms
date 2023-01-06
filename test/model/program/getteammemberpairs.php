#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

$program = zdTable('project');
$program->id->range('1-5');
$program->name->range('项目集1,项目集2,项目1,项目2,项目3');
$program->type->range('program{2},project{3}');
$program->status->range('wait');
$program->parent->range('0,0,1,1,2');
$program->grade->range('1{2},2{3}');
$program->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(5);

$team = zdTable('team');
$team->id->range('1-5');
$team->root->range('3-5');
$team->type->range('project');
$team->account->range('admin');
$team->account->setFields(array(
    array('field' => 'account1', 'range' => 'admin,user{4}'),
    array('field' => 'account2', 'range' => '[],1-4'),
));
$team->gen(5);

zdTable('user')->gen(5);

su('admin');

/**

title=测试 programModel::getTeamMemberPairs();
cid=1
pid=1

获取项目集1下所有团队成员数量     >> 5
获取项目集2下所有团队成员数量     >> 2
获取项目集1下所有团队成员真实姓名 >> A:admin
获取项目集2下所有团队成员真实姓名 >> U:用户2

*/

$programTester = new programTest();
$teams1 = $programTester->getTeamMemberPairsTest(1);
$teams2 = $programTester->getTeamMemberPairsTest(2);

r(count($teams1)) && p()        && e('5');           // 获取项目集1下所有团队成员数量
r(count($teams2)) && p()        && e('2');           // 获取项目集2下所有团队成员数量
r($teams1)        && p('admin') && e('A:admin');     // 获取项目集1下所有团队成员真实姓名
r($teams2)        && p('user2') && e('U:用户2');     // 获取项目集2下所有团队成员真实姓名
