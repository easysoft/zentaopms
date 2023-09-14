#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';

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

title=测试 programModel::batchProcessProgramName();
timeout=0
cid=1

*/

$programTester = new programTest();

$allProjects         = $programTester->program->getProjectList(0);
$undoneProjects      = $programTester->program->getProjectList(0, 'undone');
$withProgramProjects = $programTester->program->getProjectList(0, 'undone', 0, 'id_desc', null, 1);
$involvedProjects    = $programTester->program->getProjectList(0, 'undone', 0, 'id_desc', null, 1, true);

r($programTester->program->batchProcessProgramName($allProjects))         && p('3:name') && e('项目集1/项目1');         // 测试处理所有项目
r($programTester->program->batchProcessProgramName($undoneProjects))      && p('3:name') && e('项目集1/项目1');         // 测试处理未关闭项目
r($programTester->program->batchProcessProgramName($withProgramProjects)) && p('3:name') && e('项目集1/项目集1/项目1'); // 测试处理有项目集项目
r($programTester->program->batchProcessProgramName($involvedProjects))    && p('3:name') && e('项目集1/项目集1/项目1'); // 测试处理我参与项目
