#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getActiveUserTable();
timeout=0
cid=18234

- 执行screenTest模块的getActiveUserTableTest方法，参数是'2024', '01', $validProjectList  @3
- 执行screenTest模块的getActiveUserTableTest方法，参数是'2024', '01', $emptyProjectList  @0
- 执行screenTest模块的getActiveUserTableTest方法，参数是'2024', '02', $singleProject  @1
- 执行screenTest模块的getActiveUserTableTest方法，参数是'2024', '01', $validProjectList 第0条的id属性 @1
- 执行screenTest模块的getActiveUserTableTest方法，参数是'2023', '12', $validProjectList 第0条的ratio属性 @0.00%

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user_getactiveusertable', false, 2)->gen(10);
zenData('action')->loadYaml('action_getactiveusertable', false, 2)->gen(30);
zenData('team')->loadYaml('team_getactiveusertable', false, 2)->gen(15);

su('admin');

$screenTest = new screenModelTest();

$validProjectList = array(1 => '项目1', 2 => '项目2', 3 => '项目3');
$emptyProjectList = array();
$singleProject = array(1 => '项目1');

r(count($screenTest->getActiveUserTableTest('2024', '01', $validProjectList))) && p() && e('3');
r(count($screenTest->getActiveUserTableTest('2024', '01', $emptyProjectList))) && p() && e('0');
r(count($screenTest->getActiveUserTableTest('2024', '02', $singleProject))) && p() && e('1');
r($screenTest->getActiveUserTableTest('2024', '01', $validProjectList)) && p('0:id') && e('1');
r($screenTest->getActiveUserTableTest('2023', '12', $validProjectList)) && p('0:ratio') && e('0.00%');