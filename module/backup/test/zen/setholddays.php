#!/usr/bin/env php
<?php

/**

title=测试 backupZen::setHoldDays();
timeout=0
cid=15149

- 步骤1:正常输入保留天数7 @1
- 步骤2:正常输入保留天数30 @1
- 步骤3:空值输入属性holdDays @『保留天数』不能为空。
- 步骤4:零值输入(empty(0)为true)属性holdDays @『保留天数』不能为空。
- 步骤5:负数输入属性holdDays @『保留天数』应当是正整数。
- 步骤6:非数字字符串输入属性holdDays @『保留天数』应当是正整数。
- 步骤7:正整数字符串输入 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$backupTest = new backupZenTest();

r($backupTest->setHoldDaysTest(7)) && p() && e('1'); // 步骤1:正常输入保留天数7
r($backupTest->setHoldDaysTest(30)) && p() && e('1'); // 步骤2:正常输入保留天数30
r($backupTest->setHoldDaysTest(null)) && p('holdDays') && e('『保留天数』不能为空。'); // 步骤3:空值输入
r($backupTest->setHoldDaysTest(0)) && p('holdDays') && e('『保留天数』不能为空。'); // 步骤4:零值输入(empty(0)为true)
r($backupTest->setHoldDaysTest(-1)) && p('holdDays') && e('『保留天数』应当是正整数。'); // 步骤5:负数输入
r($backupTest->setHoldDaysTest('abc')) && p('holdDays') && e('『保留天数』应当是正整数。'); // 步骤6:非数字字符串输入
r($backupTest->setHoldDaysTest('15')) && p() && e('1'); // 步骤7:正整数字符串输入