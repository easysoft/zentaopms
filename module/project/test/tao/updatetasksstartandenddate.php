#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

zdTable('project')->config('execution')->gen(10);
zdTable('task')->config('task')->gen(50);

/**

title=测试 projectModel->updateTasksStartAndEndDate();
timeout=0
cid=1

*/

$projectIdList = array(11, 60 ,100);

$projectTester = new project();
r($projectTester->updateTasksStartAndEndDateTest($projectIdList[0], 'expend')) && p('0:field,old,new') && e('estStarted,2020-11-01,2020-01-01'); // 测试更新敏捷项目下的任务起止日期
r($projectTester->updateTasksStartAndEndDateTest($projectIdList[1], 'expend')) && p('0:field,old,new') && e('estStarted,2020-11-01,2020-01-01'); // 测试更新瀑布项目下的任务起止日期
r($projectTester->updateTasksStartAndEndDateTest($projectIdList[2], 'expend')) && p()                  && e('0');                                // 测试更新看板项目下的任务起止日期
r($projectTester->updateTasksStartAndEndDateTest($projectIdList[0], 'reduce')) && p('0:field,old,new') && e('estStarted,2020-01-01,2019-03-02'); // 测试更新敏捷项目下的任务起止日期
r($projectTester->updateTasksStartAndEndDateTest($projectIdList[1], 'reduce')) && p('0:field,old,new') && e('estStarted,2020-03-13,2019-05-12'); // 测试更新瀑布项目下的任务起止日期
r($projectTester->updateTasksStartAndEndDateTest($projectIdList[2], 'reduce')) && p()                  && e('0');                                // 测试更新看板项目下的任务起止日期
