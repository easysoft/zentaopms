#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

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

title=测试 programModel::getProjectList();
cid=1
pid=1

获取所有项目列表数量       >> 3
获取未完成项目列表数量     >> 2
带项目集名称的项目列表数量 >> 2
获取我参与的项目列表数量   >> 1
不带项目集名称的项目名称   >> 项目2
带项目集名称的项目名称     >> 项目集2/项目3
我参与的项目的名称         >> 项目集1/项目1

*/

$programTester = new programTest();

$allProjects         = $programTester->getProjectListTest(0);
$undoneProjects      = $programTester->getProjectListTest(0, 'undone');
$withProgramProjects = $programTester->getProjectListTest(0, 'undone', 0, 'id_desc', null, 1);
$involvedProjects    = $programTester->getProjectListTest(0, 'undone', 0, 'id_desc', null, 1, true);

r(count($allProjects))         && p() && e('3');                     // 获取所有项目列表数量
r(count($undoneProjects))      && p() && e('2');                     // 获取未完成项目列表数量
r(count($withProgramProjects)) && p() && e('2');                     // 带项目集名称的项目列表数量
r(count($involvedProjects))    && p() && e('1');                     // 获取我参与的项目列表数量
r($allProjects)                && p('4:name') && e('项目2');         // 不带项目集名称的项目名称
r($withProgramProjects)        && p('5:name') && e('项目集2/项目3'); // 带项目集名称的项目名称
r($involvedProjects)           && p('3:name') && e('项目集1/项目1'); // 我参与的项目的名称
