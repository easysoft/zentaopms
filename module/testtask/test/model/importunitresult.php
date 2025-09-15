#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::importUnitResult();
timeout=0
cid=0

- 步骤1：正常情况 - 有效XML导入 @0
- 步骤2：边界值 - 无文件上传 @0
- 步骤3：异常输入 - 格式错误XML @0
- 步骤4：权限验证 - 无有效数据XML @0
- 步骤5：业务规则 - 不同框架支持 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('Product1,Product2,Product3{2}');
$productTable->code->range('PRD001,PRD002{2}');
$productTable->status->range('normal');
$productTable->acl->range('open');
$productTable->createdBy->range('admin');
$productTable->gen(5);

$executionTable = zenData('execution');
$executionTable->id->range('1-5');
$executionTable->name->range('执行1,执行2{2}');
$executionTable->project->range('1-3');
$executionTable->status->range('wait{2},doing{3}');
$executionTable->type->range('sprint');
$executionTable->openedBy->range('admin');
$executionTable->gen(5);

zendata('testtask')->gen(0);
zendata('testcase')->gen(0);
zendata('testrun')->gen(0);
zendata('testsuite')->gen(0);
zendata('suitecase')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskTest();

// 5. 准备测试用例对象
$validTask = new stdclass();
$validTask->product = 1;
$validTask->execution = 1;
$validTask->build = 1;
$validTask->name = '单元测试导入测试';
$validTask->begin = '2024-01-01';
$validTask->end = '2024-12-31';
$validTask->status = 'wait';
$validTask->frame = 'junit';
$validTask->resultFile = '/tmp/zentao-test-xml/valid-junit.xml';
$validTask->tmpfile = '/tmp/zentao-test-xml/valid-junit.xml';

$noFileTask = new stdclass();
$noFileTask->product = 1;
$noFileTask->execution = 1;
$noFileTask->build = 1;
$noFileTask->name = '无文件测试';
$noFileTask->begin = '2024-01-01';
$noFileTask->end = '2024-12-31';
$noFileTask->status = 'wait';
$noFileTask->frame = 'junit';
$noFileTask->mockNoFile = true;

$invalidXmlTask = new stdclass();
$invalidXmlTask->product = 1;
$invalidXmlTask->execution = 1;
$invalidXmlTask->build = 1;
$invalidXmlTask->name = '无效XML测试';
$invalidXmlTask->begin = '2024-01-01';
$invalidXmlTask->end = '2024-12-31';
$invalidXmlTask->status = 'wait';
$invalidXmlTask->frame = 'junit';
$invalidXmlTask->resultFile = '/tmp/zentao-test-xml/malformed.xml';
$invalidXmlTask->tmpfile = '/tmp/zentao-test-xml/malformed.xml';

$noDataTask = new stdclass();
$noDataTask->product = 1;
$noDataTask->execution = 1;
$noDataTask->build = 1;
$noDataTask->name = '无测试数据XML测试';
$noDataTask->begin = '2024-01-01';
$noDataTask->end = '2024-12-31';
$noDataTask->status = 'wait';
$noDataTask->frame = 'junit';
$noDataTask->resultFile = '/tmp/zentao-test-xml/invalid.xml';
$noDataTask->tmpfile = '/tmp/zentao-test-xml/invalid.xml';

$phpunitTask = new stdclass();
$phpunitTask->product = 2;
$phpunitTask->execution = 2;
$phpunitTask->build = 2;
$phpunitTask->name = 'PHPUnit格式测试';
$phpunitTask->begin = '2024-01-01';
$phpunitTask->end = '2024-12-31';
$phpunitTask->status = 'wait';
$phpunitTask->frame = 'phpunit';
$phpunitTask->resultFile = '/tmp/zentao-test-xml/valid-junit.xml';
$phpunitTask->tmpfile = '/tmp/zentao-test-xml/valid-junit.xml';

// 6. 强制要求：必须包含至少5个测试步骤
r($testtaskTest->importUnitResultTest($validTask)) && p() && e('0'); // 步骤1：正常情况 - 有效XML导入
r($testtaskTest->importUnitResultTest($noFileTask)) && p() && e('0'); // 步骤2：边界值 - 无文件上传
r($testtaskTest->importUnitResultTest($invalidXmlTask)) && p() && e('0'); // 步骤3：异常输入 - 格式错误XML
r($testtaskTest->importUnitResultTest($noDataTask)) && p() && e('0'); // 步骤4：权限验证 - 无有效数据XML
r($testtaskTest->importUnitResultTest($phpunitTask)) && p() && e('0'); // 步骤5：业务规则 - 不同框架支持