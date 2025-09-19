#!/usr/bin/env php
<?php

/**

title=测试 commonModel::judgeSuhosinSetting();
timeout=0
cid=0

- 测试步骤1：正常小数值输入，期望不超过限制 @0
- 测试步骤2：边界值（等于max_input_vars），期望不超过限制 @0
- 测试步骤3：超过max_input_vars限制，期望返回true @1
- 测试步骤4：零值输入测试，期望不超过限制 @0
- 测试步骤5：大幅超过限制值，期望返回true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->judgeSuhosinSettingTest(100))    && p('') && e('0'); // 测试步骤1：正常小数值输入，期望不超过限制
r($commonTest->judgeSuhosinSettingTest(10000))  && p('') && e('0'); // 测试步骤2：边界值（等于max_input_vars），期望不超过限制
r($commonTest->judgeSuhosinSettingTest(10001))  && p('') && e('1'); // 测试步骤3：超过max_input_vars限制，期望返回true
r($commonTest->judgeSuhosinSettingTest(0))      && p('') && e('0'); // 测试步骤4：零值输入测试，期望不超过限制
r($commonTest->judgeSuhosinSettingTest(50000))  && p('') && e('1'); // 测试步骤5：大幅超过限制值，期望返回true