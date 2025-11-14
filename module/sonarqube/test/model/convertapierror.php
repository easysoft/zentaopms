#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::convertApiError();
timeout=0
cid=18381

- 步骤1：空字符串输入 @0
- 步骤2：数组输入处理 @array error
- 步骤3：非字符串类型输入 @123
- 步骤4：正确的错误映射匹配 @权限不足
- 步骤5：正则匹配错误信息 @无法创建项目，项目标识已存在：新项目
- 步骤6：未匹配的错误信息 @Unknown error message
- 步骤7：无apiErrorMap配置 @Any message

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';

su('admin');

$sonarqubeTest = new sonarqubeTest();

// 准备测试数据 - 设置错误映射配置
global $tester;
$sonarqube = $tester->loadModel('sonarqube');
$sonarqube->lang->sonarqube->apiErrorMap[3] = 'Could not create Project.';

r($sonarqubeTest->convertApiErrorTest(''))                          && p() && e('0');        // 步骤1：空字符串输入
r($sonarqubeTest->convertApiErrorTest(array('array error', 'second'))) && p() && e('array error'); // 步骤2：数组输入处理
r($sonarqubeTest->convertApiErrorTest(123))                         && p() && e('123');      // 步骤3：非字符串类型输入
r($sonarqubeTest->convertApiErrorTest('Could not create Project.')) && p() && e('权限不足'); // 步骤4：正确的错误映射匹配
r($sonarqubeTest->convertApiErrorTest('Could not create Project, key already exists: 新项目 ')) && p() && e('无法创建项目，项目标识已存在：新项目'); // 步骤5：正则匹配错误信息
r($sonarqubeTest->convertApiErrorTest('Unknown error message'))     && p() && e('Unknown error message'); // 步骤6：未匹配的错误信息

// 步骤7：测试无apiErrorMap配置的情况
unset($sonarqube->lang->sonarqube->apiErrorMap);
r($sonarqubeTest->convertApiErrorTest('Any message'))               && p() && e('Any message'); // 步骤7：无apiErrorMap配置