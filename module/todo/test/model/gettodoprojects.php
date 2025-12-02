#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 todoModel->getTodoProjects();
timeout=0
cid=19262

- 验证task获得的键值对的个数 @4
- 验证taskID为1对应的projectID属性1 @11
- 验证taskID为2对应的projectID属性2 @12
- 验证taskID为3对应的projectID属性3 @13
- 验证taskID为4对应的projectID属性4 @14

*/

su('admin');

zenData('todo')->loadYaml('gettodoprojects')->gen(10);
zenData('task')->gen(4);

global $tester;
$tester->loadModel('todo');

$ids  = range(1,10);
$list = $tester->todo->getByList($ids);

$todoList = array();
foreach($list as $todo) $todoList[$todo->type][$todo->objectID] = $todo;

$projectList = $tester->todo->getTodoProjects($todoList);

r(count($projectList['task'])) && p()    && e('4');  // 验证task获得的键值对的个数
r($projectList['task'])        && p('1') && e('11'); // 验证taskID为1对应的projectID
r($projectList['task'])        && p('2') && e('12'); // 验证taskID为2对应的projectID
r($projectList['task'])        && p('3') && e('13'); // 验证taskID为3对应的projectID
r($projectList['task'])        && p('4') && e('14'); // 验证taskID为4对应的projectID
