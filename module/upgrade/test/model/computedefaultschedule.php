#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 upgradeModel->computeDefaultSchedule();
timeout=0
cid=1

- 给项目和执行生成默认的日历数据
 - 第0条的project属性 @0
 - 第0条的type属性 @project
 - 第0条的name属性 @1
 - 第0条的status属性 @wait
 - 第0条的model属性 @scrum

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$projectTable = zenData('project');
$projectTable->name->range('1-10');
$projectTable->project->range('0{5},1{5}');
$projectTable->model->range('scrum{5},[]{5}');
$projectTable->type->range('project{5},sprint{5}');
$projectTable->gen(10);

zenData('holiday')->gen(0);
zenData('user')->gen(5);
su('admin');

$upgrade = new upgradeModelTest();
r($upgrade->computeDefaultScheduleTest()) && p('0:project,type,name,status,model') && e('0,project,1,wait,scrum'); // 给项目和执行生成默认的日历数据
