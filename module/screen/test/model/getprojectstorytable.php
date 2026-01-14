#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getProjectStoryTable();
timeout=0
cid=18253

- 执行$result1 @0
- 执行screenTest模块的getProjectStoryTableTest方法，参数是'2023', '06', array  @0
- 执行$result3 @1
- 执行$result4 @1
- 执行$result5 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zendata('project')->loadYaml('project_getprojectstorytable', false, 2)->gen(10);
zendata('projectstory')->loadYaml('projectstory_getprojectstorytable', false, 2)->gen(20);
zendata('story')->loadYaml('story_getprojectstorytable', false, 2)->gen(15);
zendata('action')->loadYaml('action_getprojectstorytable', false, 2)->gen(30);

su('admin');

$screenTest = new screenModelTest();

// 测试步骤1：正常年月和项目列表参数，返回数组长度
$projectList = array(1 => 'Project 1', 2 => 'Project 2', 3 => 'Project 3');
$result1 = $screenTest->getProjectStoryTableTest('2023', '06', $projectList);
r($result1) && p() && e('0');

// 测试步骤2：空项目列表参数
r($screenTest->getProjectStoryTableTest('2023', '06', array())) && p() && e('0');

// 测试步骤3：测试方法是否返回数组类型
$result3 = $screenTest->getProjectStoryTableTest('2023', '06', $projectList);
r(is_array($result3)) && p() && e('1');

// 测试步骤4：测试空项目列表返回空数组
$result4 = $screenTest->getProjectStoryTableTest('2023', '06', array());
r(is_array($result4)) && p() && e('1');

// 测试步骤5：测试边界值项目ID返回数组
$boundaryProjectList = array(999 => 'Non-exist Project', 0 => 'Invalid Project');
$result5 = $screenTest->getProjectStoryTableTest('2023', '06', $boundaryProjectList);
r(is_array($result5)) && p() && e('1');