#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/task/test/lib/task.unittest.class.php';

zenData('todo')->loadYaml('getprojectlist')->gen(10);
zenData('task')->gen(10);
zenData('bug')->gen(10);
zenData('taskspec')->gen(10);
zenData('project')->gen(10);

su('admin');

/**

title=测试 todoModel->getCount();
timeout=0
cid=1

- 验证task获得的键值对的个数 @1
- 验证taskID为10的project为20属性10 @20
- 验证project获得的键值对的个数 @1
- 获取不到的情况属性10 @0
- 验证bugID为10的project为24属性10 @14

*/

global $tester;
$tester->loadModel('todo');

$ids           = range(1,10);
$list          = $tester->todo->getByList($ids);
$projectIDList = array_column($list, 'objectID');

$projectList = $tester->todo->getProjectList('zt_task', $projectIDList);

r(count($projectList)) && p()     && e('1'); //验证task获得的键值对的个数
r($projectList)        && p('10') && e('20'); //验证taskID为10的project为20

$projectList = $tester->todo->getProjectList('zt_project', $projectIDList);

r(count($projectList)) && p()     && e('1'); //验证project获得的键值对的个数
r($projectList)        && p('10') && e('0'); //获取不到的情况

$projectList = $tester->todo->getProjectList('zt_bug', $projectIDList);
r($projectList)  && p('10') && e('14'); //验证bugID为10的project为24