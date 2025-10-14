#!/usr/bin/env php
<?php

/**

title=测试 projectZen::expandExecutionIdList();
timeout=0
cid=0

- 执行projectTest模块的expandExecutionIdListTest方法，参数是array  @0
- 执行projectTest模块的expandExecutionIdListTest方法，参数是'single_execution'  @1
- 执行projectTest模块的expandExecutionIdListTest方法，参数是'nested_executions'  @3
- 执行projectTest模块的expandExecutionIdListTest方法，参数是'multi_level_nesting'  @7
- 执行projectTest模块的expandExecutionIdListTest方法，参数是'mixed_executions'  @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/expandexecutionidlist.unittest.class.php';

su('admin');

$projectTest = new expandExecutionIdListTest();

r($projectTest->expandExecutionIdListTest(array())) && p() && e('0');
r($projectTest->expandExecutionIdListTest('single_execution')) && p() && e('1');
r($projectTest->expandExecutionIdListTest('nested_executions')) && p() && e('3');
r($projectTest->expandExecutionIdListTest('multi_level_nesting')) && p() && e('7');
r($projectTest->expandExecutionIdListTest('mixed_executions')) && p() && e('4');