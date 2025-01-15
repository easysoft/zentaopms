#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

/**

title=测试 executionModel::getToAndCcList();
timeout=0
cid=1

*/

su('admin');
zenData('project')->loadYaml('execution')->gen(20);
zenData('team')->loadYaml('team')->gen(10);
zenData('user')->gen(110);

$executionIdList = array(101, 102, 103, 104, 105);
