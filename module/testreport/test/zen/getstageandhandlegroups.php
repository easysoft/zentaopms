#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::getStageAndHandleGroups();
timeout=0
cid=0

- 执行testreportTest模块的getStageAndHandleGroupsTest方法，参数是array  @Array
- 执行testreportTest模块的getStageAndHandleGroupsTest方法，参数是array  @Array
- 执行testreportTest模块的getStageAndHandleGroupsTest方法，参数是array  @Array
- 执行testreportTest模块的getStageAndHandleGroupsTest方法，参数是array  @Array
- 执行testreportTest模块的getStageAndHandleGroupsTest方法，参数是array  @Array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

zenData('bug')->loadYaml('bug_getstageandhandlegroups', false, 2)->gen(20);
zenData('user')->loadYaml('user_getstageandhandlegroups', false, 2)->gen(10);

su('admin');

$testreportTest = new testreportTest();

r($testreportTest->getStageAndHandleGroupsTest(array(1, 2), '2024-01-01', '2024-01-03', array(1, 2))) && p() && e('Array');
r($testreportTest->getStageAndHandleGroupsTest(array(), '2024-01-01', '2024-01-02', array())) && p() && e('Array');
r($testreportTest->getStageAndHandleGroupsTest(array(1), '2024-01-01', '2024-01-01', array(1))) && p() && e('Array');
r($testreportTest->getStageAndHandleGroupsTest(array(1), '2023-12-31', '2024-01-01', array())) && p() && e('Array');
r($testreportTest->getStageAndHandleGroupsTest(array(1, 2, 3), '2024-01-01', '2024-01-05', array(1, 2, 3))) && p() && e('Array');