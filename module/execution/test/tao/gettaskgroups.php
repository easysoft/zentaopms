#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('task')->gen(100);

su('admin');

/**

title=测试executionModel->getTaskGroups();
timeout=0
cid=1

*/

$executionIDList = array(0, 103, 10);

global $tester;
$execution = $tester->loadModel('execution');
r($execution->getTaskGroups($executionIDList[1])[27][9]) && p('3:name') && e('开发任务13'); // 查询存在任务的执行
r($execution->getTaskGroups($executionIDList[2]))        && p()         && e('0');          // 查询不存在任务的执行
r($execution->getTaskGroups($executionIDList[0]))        && p()         && e('0');          // 查询错误的执行
