#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

$project = zenData('project');
$project->id->range('11-19,101-109');
$project->project->range('11-19');
$project->name->setFields(array(
    array('field' => 'name1', 'range' => '项目{9},迭代{9}'),
    array('field' => 'name2', 'range' => '11-19,101-109')
));
$project->code->setFields(array(
    array('field' => 'name1', 'range' => 'project{9},execution{9}'),
    array('field' => 'name2', 'range' => '11-19,101-109')
));
$project->model->range("scrum{9},[]{9}");
$project->auth->range("[]");
$project->path->range("[]");
$project->type->range("project{9},sprint{9}");
$project->grade->range("1");
$project->days->range("1");
$project->status->range("wait,doing,suspended,closed");
$project->desc->range("[]");
$project->budget->range("100000,200000");
$project->budgetUnit->range("CNY");
$project->percent->range("0-0");
$project->openedDate->range("`2023-05-01 10:00:10`");
$project->gen(18);

zenData('task')->gen(10);

/**

title=测试 projectModel->getTotalTaskByProject();
timeout=0
cid=17914

- 获取id为11的项目下未开始的task数量第11条的waitTasks属性 @1
- 获取id为15的项目下所有的task数量第15条的allTasks属性 @1
- 获取id为16的项目下所有的task数量第16条的allTasks属性 @1
- 获取id为27的项目下未开始的task数量第27条的waitTasks属性 @Error: Cannot get index 27.
- 获取项目为空的task数量 @0

*/

global $tester;
$tester->loadModel('project');

$projectIdList = array(11, 12, 13, 14, 15, 16, 27);
$result1 = $tester->project->getTotalTaskByProject($projectIdList);
$result2 = $tester->project->getTotalTaskByProject(array());

r($result1) && p('11:waitTasks') && e('1');                           // 获取id为11的项目下未开始的task数量
r($result1) && p('15:allTasks')  && e('1');                           // 获取id为15的项目下所有的task数量
r($result1) && p('16:allTasks')  && e('1');                           // 获取id为16的项目下所有的task数量
r($result1) && p('27:waitTasks') && e('Error: Cannot get index 27.'); // 获取id为27的项目下未开始的task数量
r($result2) && p()               && e('0');                           // 获取项目为空的task数量