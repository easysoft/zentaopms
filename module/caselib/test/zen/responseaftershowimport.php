#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::responseAfterShowImport();
timeout=0
cid=0

- 步骤1:测试空用例数据场景 @0
- 步骤2:测试正常用例数据 @1
- 步骤3:测试超过最大限制但未设置maxImport @0
- 步骤4:测试超过最大限制且设置maxImport第1页 @1
- 步骤5:测试分页后空数据场景 @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备(根据需要配置)
zenData('case')->gen(0);
zenData('caselib')->gen(0);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$caselibTest = new caselibZenTest();

// 准备测试数据
$emptyData = array();
$normalData = array();
for($i = 1; $i <= 50; $i++)
{
    $case = new stdclass();
    $case->id = $i;
    $case->title = "测试用例{$i}";
    $normalData[$i] = $case;
}

$overLimitData = array();
for($i = 1; $i <= 150; $i++)
{
    $case = new stdclass();
    $case->id = $i;
    $case->title = "测试用例{$i}";
    $overLimitData[$i] = $case;
}

// 模拟配置
global $config;
$config->file = new stdclass();
$config->file->maxImport = 100;

// 创建临时文件用于测试
$tempFile = tempnam(sys_get_temp_dir(), 'test_import_');
file_put_contents($tempFile, 'test data');

// 设置session
global $app;
$app->session->set('fileImport', $tempFile);

// 5. 强制要求:必须包含至少5个测试步骤
r($caselibTest->responseAfterShowImportTest(1, $emptyData, 0, 1, 0)) && p() && e('0'); // 步骤1:测试空用例数据场景
r($caselibTest->responseAfterShowImportTest(1, $normalData, 0, 1, 0)) && p() && e('1'); // 步骤2:测试正常用例数据
r($caselibTest->responseAfterShowImportTest(1, $overLimitData, 0, 1, 0)) && p() && e('0'); // 步骤3:测试超过最大限制但未设置maxImport
r($caselibTest->responseAfterShowImportTest(1, $overLimitData, 50, 1, 0)) && p() && e('1'); // 步骤4:测试超过最大限制且设置maxImport第1页
r($caselibTest->responseAfterShowImportTest(1, $overLimitData, 50, 4, 0)) && p() && e('0'); // 步骤5:测试分页后空数据场景