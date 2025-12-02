#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::getDataForImport();
timeout=0
cid=15545

- 步骤1：正常导入CSV数据（跳过空标题行） @3
- 步骤2：从缓存文件读取数据 @缓存用例
- 步骤3：验证第一个用例标题 @测试用例1
- 步骤4：处理模块字段格式解析 @1
- 步骤5：验证步骤字段存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('testsuite');
$table->id->range('1-10');
$table->name->range('用例库{1-10}');
$table->type->range('public');
$table->addedBy->range('admin');
$table->gen(5);

// 创建测试CSV文件（必须在zentao路径下）
global $app;
$csvFile = $app->getBasePath() . 'test_import_cases.csv';
$csvContent = "标题,所属模块,用例类型,优先级,前置条件,关键词,适用阶段,步骤,预期\n";
$csvContent .= "测试用例1,模块1(#1),功能测试,3,登录系统,测试,单元测试阶段,\"1. 输入用户名\n2. 输入密码\n3. 点击登录\",\"1. 显示用户名输入框\n2. 显示密码输入框\n3. 跳转到首页\"\n";
$csvContent .= "测试用例2,模块2(#2),性能测试,2,无,性能,集成测试阶段,1. 发送请求,1. 响应正常\n";
$csvContent .= ",模块3(#3),功能测试,1,无,空标题,,\n"; // 空标题行，应被跳过
$csvContent .= "测试用例4,模块4,接口测试,4,准备数据,接口,\"系统测试阶段\n验收测试阶段\",1. 调用接口,1. 返回正确数据\n";
file_put_contents($csvFile, $csvContent);

// 创建缓存文件用于测试
$cacheFile = $app->getBasePath() . 'test_import_cache.tmp';
$cacheData = array(
    'caseData' => array(
        1 => (object)array(
            'title' => '缓存用例',
            'type' => 'feature',
            'module' => '5'
        )
    )
);
file_put_contents($cacheFile, serialize($cacheData));

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$caselibTest = new caselibTest();

// 设置session数据模拟导入文件
$_SESSION['fileImport'] = $csvFile;

// 准备字段映射
$fields = array(
    '标题' => 'title',
    '所属模块' => 'module',
    '用例类型' => 'type',
    '优先级' => 'pri',
    '前置条件' => 'precondition',
    '关键词' => 'keywords',
    '适用阶段' => 'stage',
    '步骤' => 'stepDesc',
    '预期' => 'stepExpect'
);

// 创建临时文件用于新数据缓存
$tmpFile = $app->getBasePath() . 'test_new_import.tmp';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($caselibTest->getDataForImportTest(0, $tmpFile, $fields, 'caseData_count')) && p() && e('3'); // 步骤1：正常导入CSV数据（跳过空标题行）
r($caselibTest->getDataForImportTest(1, $cacheFile, $fields, 'first_case_title')) && p() && e('缓存用例'); // 步骤2：从缓存文件读取数据
r($caselibTest->getDataForImportTest(0, $tmpFile, $fields, 'first_case_title')) && p() && e('测试用例1'); // 步骤3：验证第一个用例标题
r($caselibTest->getDataForImportTest(0, $tmpFile, $fields, 'first_case_module')) && p() && e('1'); // 步骤4：处理模块字段格式解析
r($caselibTest->getDataForImportTest(0, $tmpFile, $fields, 'has_steps')) && p() && e('1'); // 步骤5：验证步骤字段存在

// 清理测试文件
unlink($csvFile);
unlink($cacheFile);
if(file_exists($tmpFile)) unlink($tmpFile);