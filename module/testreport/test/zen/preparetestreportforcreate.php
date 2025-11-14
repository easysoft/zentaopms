#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::prepareTestreportForCreate();
timeout=0
cid=19135

- 步骤1：正常情况
 - 属性title @测试报告标题
 - 属性owner @admin
 - 属性project @1
- 步骤2：缺少title @『标题』不能为空。
- 步骤3：缺少owner @『负责人』不能为空。
- 步骤4：时间验证错误 @『结束日期』应当不小于『2024-01-31』。
- 步骤5：空execution情况属性project @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备
$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('执行1,执行2,执行3,执行4,执行5');
$execution->type->range('execution{3},project{2}');
$execution->status->range('wait{2},doing{2},done{1}');
$execution->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testreportTest = new testreportTest();

// 5. 模拟POST数据设置
global $app;
$app->post = new stdClass();

// 测试步骤1：正常输入情况
$app->post->execution = '1';
$app->post->title = '测试报告标题';
$app->post->owner = 'admin';
$app->post->product = '1';
$app->post->objectID = '1';
$app->post->objectType = 'execution';
$app->post->begin = '2024-01-01';
$app->post->end = '2024-01-31';
$app->post->members = array('admin', 'user1');
$app->post->tasks = '1,2';
$app->post->builds = '1';
$app->post->cases = '1,2,3';
$app->post->stories = '1,2';
$app->post->bugs = '1';
$app->post->report = '测试报告内容';
$app->post->uid = '123456';
r($testreportTest->prepareTestreportForCreateTest()) && p('title,owner,project') && e('测试报告标题,admin,1'); // 步骤1：正常情况

// 测试步骤2：缺少必填字段title
$app->post->title = '';
$result2 = $testreportTest->prepareTestreportForCreateTest();
$error2 = is_array($result2) && isset($result2['title[]']) ? $result2['title[]'][0] : '';
r($error2) && p() && e('『标题』不能为空。'); // 步骤2：缺少title

// 测试步骤3：缺少必填字段owner
$app->post->title = '测试报告标题';
$app->post->owner = '';
$result3 = $testreportTest->prepareTestreportForCreateTest();
$error3 = is_array($result3) && isset($result3['owner[]']) ? $result3['owner[]'][0] : '';
r($error3) && p() && e('『负责人』不能为空。'); // 步骤3：缺少owner

// 测试步骤4：结束时间早于开始时间
$app->post->owner = 'admin';
$app->post->begin = '2024-01-31';
$app->post->end = '2024-01-01';
$result4 = $testreportTest->prepareTestreportForCreateTest();
$error4 = is_array($result4) && isset($result4['end']) ? $result4['end'][0] : '';
r($error4) && p() && e('『结束日期』应当不小于『2024-01-31』。'); // 步骤4：时间验证错误

// 测试步骤5：空execution的情况
$app->post->execution = '0';
$app->post->begin = '2024-01-01';
$app->post->end = '2024-01-31';
r($testreportTest->prepareTestreportForCreateTest()) && p('project') && e('0'); // 步骤5：空execution情况