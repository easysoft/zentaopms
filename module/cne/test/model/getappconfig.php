#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getAppConfig();
timeout=0
cid=0

- 正常实例ID获取配置属性code @200
- 不存在的实例ID @0
- 无效实例ID @0
- 负数实例ID @0
- 另一个有效实例ID验证属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->getAppConfigTest(1)) && p('code') && e('200'); // 正常实例ID获取配置
r($cneTest->getAppConfigTest(999)) && p() && e('0'); // 不存在的实例ID
r($cneTest->getAppConfigTest(0)) && p() && e('0'); // 无效实例ID
r($cneTest->getAppConfigTest(-1)) && p() && e('0'); // 负数实例ID
r($cneTest->getAppConfigTest(2)) && p('code') && e('200'); // 另一个有效实例ID验证