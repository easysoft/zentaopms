#!/usr/bin/env php
<?php

/**

title=测试 myTao::fetchTasksBySearch();
timeout=0
cid=0

- 执行myTest模块的fetchTasksBySearchTest方法，参数是"`name` like '%任务%'", 'workTask', 'admin', array  @0
- 执行myTest模块的fetchTasksBySearchTest方法，参数是"`assignedTo` = 'admin'", 'workTask', 'admin', array  @0
- 执行myTest模块的fetchTasksBySearchTest方法，参数是"t1.`execution` = '1'", 'workTask', 'admin', array  @0
- 执行myTest模块的fetchTasksBySearchTest方法，参数是'1', 'workTask', 'admin', array  @0
- 执行myTest模块的fetchTasksBySearchTest方法，参数是'1', 'workTask', 'admin', array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('task')->gen(0);
zenData('execution')->gen(0);
zenData('project')->gen(0);
zenData('story')->gen(0);
zenData('taskteam')->gen(0);

su('admin');

$myTest = new myTest();

r($myTest->fetchTasksBySearchTest("`name` like '%任务%'", 'workTask', 'admin', array(), 'id_desc', 0, null)) && p() && e('0');
r($myTest->fetchTasksBySearchTest("`assignedTo` = 'admin'", 'workTask', 'admin', array(), 'id_desc', 0, null)) && p() && e('0');
r($myTest->fetchTasksBySearchTest("t1.`execution` = '1'", 'workTask', 'admin', array(), 'id_desc', 0, null)) && p() && e('0');
r($myTest->fetchTasksBySearchTest('1', 'workTask', 'admin', array(), 'id_desc', 0, null)) && p() && e('0');
r($myTest->fetchTasksBySearchTest('1', 'workTask', 'admin', array(), 'id_desc', 5, null)) && p() && e('0');