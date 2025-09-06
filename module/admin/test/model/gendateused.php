#!/usr/bin/env php
<?php

/**

title=测试 adminModel::genDateUsed();
timeout=0
cid=0

- 步骤1：正常情况下管理员用户有操作记录，检查年份属性year @0
- 步骤2：检查返回对象包含月份信息属性month @0
- 步骤3：检查返回对象包含天数信息属性day @0
- 步骤4：检查返回对象包含小时信息属性hour @0
- 步骤5：检查返回对象包含分钟和秒信息
 - 属性minute @0
 - 属性secound @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

zenData('action')->loadYaml('action', false, 2)->gen(10);

su('admin');

$adminTest = new adminTest();

r($adminTest->genDateUsedTest()) && p('year') && e('0'); // 步骤1：正常情况下管理员用户有操作记录，检查年份
r($adminTest->genDateUsedTest()) && p('month') && e('0'); // 步骤2：检查返回对象包含月份信息
r($adminTest->genDateUsedTest()) && p('day') && e('0'); // 步骤3：检查返回对象包含天数信息
r($adminTest->genDateUsedTest()) && p('hour') && e('0'); // 步骤4：检查返回对象包含小时信息
r($adminTest->genDateUsedTest()) && p('minute,secound') && e('0,0'); // 步骤5：检查返回对象包含分钟和秒信息