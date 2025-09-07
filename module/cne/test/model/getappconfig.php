#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getAppConfig();
cid=0

- 测试步骤1：正常实例ID获取应用配置 >> 期望返回对象类型
- 测试步骤2：不存在的实例ID >> 期望返回false
- 测试步骤3：无效实例ID（0） >> 期望返回false
- 测试步骤4：边界值实例ID（999） >> 期望返回false
- 测试步骤5：验证数据结构正确性 >> 期望包含资源信息

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->getAppConfigTest(1)) && p() && e('object'); // 正常实例ID获取配置
r($cneTest->getAppConfigTest(999)) && p() && e('false'); // 不存在的实例ID  
r($cneTest->getAppConfigTest(0)) && p() && e('false'); // 无效实例ID
r($cneTest->getAppConfigTest(-1)) && p() && e('false'); // 负数实例ID
r($cneTest->getAppConfigTest(2)) && p() && e('object'); // 另一个有效实例ID验证