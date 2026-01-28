#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::filterMemOptions();
timeout=0
cid=16793

- 步骤1：1GB内存，期望返回6个选项 @6
- 步骤2：512MB内存，期望返回7个选项 @7
- 步骤3：128MB内存，期望返回所有9个选项 @9
- 步骤4：32GB内存，期望返回1个选项 @1
- 步骤5：0值输入，期望返回所有选项 @9
- 步骤6：检查1GB选项的键值属性1048576 @1GB
- 步骤7：检查2GB选项的显示文本属性2097152 @2GB

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$instanceTest = new instanceModelTest();

// 4. 测试步骤
r(count($instanceTest->filterMemOptionsTest(1024 * 1024))) && p() && e('6'); // 步骤1：1GB内存，期望返回6个选项
r(count($instanceTest->filterMemOptionsTest(512 * 1024))) && p() && e('7'); // 步骤2：512MB内存，期望返回7个选项
r(count($instanceTest->filterMemOptionsTest(128 * 1024))) && p() && e('9'); // 步骤3：128MB内存，期望返回所有9个选项
r(count($instanceTest->filterMemOptionsTest(32768 * 1024))) && p() && e('1'); // 步骤4：32GB内存，期望返回1个选项
r(count($instanceTest->filterMemOptionsTest(0))) && p() && e('9'); // 步骤5：0值输入，期望返回所有选项
r($instanceTest->filterMemOptionsTest(1024 * 1024)) && p('1048576') && e('1GB'); // 步骤6：检查1GB选项的键值
r($instanceTest->filterMemOptionsTest(2048 * 1024)) && p('2097152') && e('2GB'); // 步骤7：检查2GB选项的显示文本