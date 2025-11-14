#!/usr/bin/env php
<?php

/**

title=测试 metricZen::endTime();
timeout=0
cid=17186

- 步骤1：正常计算时间差，验证返回值格式 @string
- 步骤2：测试时间差为非负值 @1
- 步骤3：测试较小时间差的计算 @string
- 步骤4：测试返回值数字格式化精度 @1
- 步骤5：测试无效时间输入的处理 @string

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricZenTest = new metricZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
$currentTime = microtime(true);
r(gettype($metricZenTest->endTimeZenTest($currentTime))) && p() && e('string'); // 步骤1：正常计算时间差，验证返回值格式
$timeStart = microtime(true);
$result = $metricZenTest->endTimeZenTest($timeStart);
r(floatval($result) >= 0 ? '1' : '0') && p() && e('1'); // 步骤2：测试时间差为非负值
$beginTime = microtime(true) - 0.001;
r(gettype($metricZenTest->endTimeZenTest($beginTime))) && p() && e('string'); // 步骤3：测试较小时间差的计算
$timeResult = $metricZenTest->endTimeZenTest($currentTime - 1.23456);
r(strlen($timeResult) == 7 && strpos($timeResult, '.') == 1 ? '1' : '0') && p() && e('1'); // 步骤4：测试返回值数字格式化精度
r(gettype($metricZenTest->endTimeZenTest(0))) && p() && e('string'); // 步骤5：测试无效时间输入的处理