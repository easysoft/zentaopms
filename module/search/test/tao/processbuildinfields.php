#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processBuildinFields();
timeout=0
cid=0

- 步骤1：处理 task 搜索配置时保留原始字段 @ID
- 步骤2：处理 task 搜索配置时保留原始参数 @select
- 步骤3：处理 projectBuild 搜索配置时保留别名模块字段 @Status
- 步骤4：处理 executionCase 搜索配置时保留原始操作符 @=
- 步骤5：处理 projectStory 搜索配置时仍保留基础字段 @1
- 步骤6：非内置模块会直接返回原始配置 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$search = new searchTaoTest();

$searchConfig = array(
    'fields' => array('id' => 'ID', 'status' => 'Status'),
    'params' => array(
        'id'     => array('operator' => '=', 'control' => 'input',  'values' => ''),
        'status' => array('operator' => '=', 'control' => 'select', 'values' => array('wait' => 'wait')),
    ),
);

$taskResult         = $search->processBuildinFieldsTest('task', $searchConfig);
$projectBuildResult = $search->processBuildinFieldsTest('projectBuild', $searchConfig);
$executionCaseResult = $search->processBuildinFieldsTest('executionCase', $searchConfig);
$projectStoryResult = $search->processBuildinFieldsTest('projectStory', $searchConfig);
$customResult       = $search->processBuildinFieldsTest('customModule', $searchConfig);

r($taskResult['fields']['id'])                           && p() && e('ID');     // 步骤1：处理 task 搜索配置时保留原始字段
r($taskResult['params']['status']['control'])           && p() && e('select'); // 步骤2：处理 task 搜索配置时保留原始参数
r($projectBuildResult['fields']['status'])              && p() && e('Status'); // 步骤3：处理 projectBuild 搜索配置时保留别名模块字段
r($executionCaseResult['params']['id']['operator'])     && p() && e('=');      // 步骤4：处理 executionCase 搜索配置时保留原始操作符
r(isset($projectStoryResult['fields']['id']))           && p() && e('1');      // 步骤5：处理 projectStory 搜索配置时仍保留基础字段
r($customResult == $searchConfig)                       && p() && e('1');      // 步骤6：非内置模块会直接返回原始配置
