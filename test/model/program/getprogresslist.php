#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1-8');
$program->name->setFields(array(
    array('field' => 'name1', 'range' => '项目集{2},项目{3},迭代{3}'),
    array('field' => 'name2', 'range' => '1-8')
));
$program->type->range('program{2},project{3},sprint{3}');
$program->status->range('doing');
$program->parent->range('0,0,1,1,2,3,4,5');
$program->grade->range('1{2},2{3},1{3}');
$program->project->range('0{5},3-5');
$program->path->range('1,2,`1,3`,`1,4`,`2,5`,`3,6`,`4,7`,`5,8`')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(8);

$task = zdTable('task');
$task->name->prefix('任务')->range('1-3');
$task->project->range('3-5');
$task->execution->range('6-8');
$task->estimate->range('6-8');
$task->consumed->range('1,3,2');
$task->left->range('5,4,6');
$task->status->range('doing');
$task->assignedTo->range('admin');
$task->gen(3);

/**

title=测试 programModee::getProgressList();
cid=1
pid=1

获取项目和项目集的个数 >> 5
获取id=2的项目的进度   >> 25
获取id=4的项目集的进度 >> 43

*/

$programTester = new programTest();
$progressList = $programTester->getProgressListTest();

r(count($progressList)) && p()    && e('5');  // 获取项目和项目集的个数
r($progressList)        && p('2') && e('25'); // 获取id=2的项目的进度
r($progressList)        && p('4') && e('43'); // 获取id=4的项目集的进度
