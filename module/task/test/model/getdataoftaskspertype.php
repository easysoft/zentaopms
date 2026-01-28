#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('task')->loadYaml('task')->gen(30);

/**

title=taskModel->getDataOfTasksPerType();
timeout=0
cid=18805

- 统计类型为devel的任务数量
 - 第devel条的name属性 @开发
 - 第devel条的value属性 @4
- 统计类型为study的任务数量
 - 第study条的name属性 @研究
 - 第study条的value属性 @4
- 统计类型为discuss的任务数量
 - 第discuss条的name属性 @讨论
 - 第discuss条的value属性 @4
- 统计类型为ui的任务数量
 - 第ui条的name属性 @界面
 - 第ui条的value属性 @4

*/

global $tester;
$taskModule = $tester->loadModel('task');

r($taskModule->getDataOfTasksPerType()) && p('devel:name,value')   && e('开发,4'); //统计类型为devel的任务数量
r($taskModule->getDataOfTasksPerType()) && p('study:name,value')   && e('研究,4'); //统计类型为study的任务数量
r($taskModule->getDataOfTasksPerType()) && p('discuss:name,value') && e('讨论,4'); //统计类型为discuss的任务数量
r($taskModule->getDataOfTasksPerType()) && p('ui:name,value')      && e('界面,4'); //统计类型为ui的任务数量