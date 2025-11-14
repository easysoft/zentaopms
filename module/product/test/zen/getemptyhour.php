#!/usr/bin/env php
<?php

/**

title=测试 productZen::getEmptyHour();
timeout=0
cid=17580

- 步骤1:验证返回对象类型 @stdClass
- 步骤2:验证totalEstimate属性属性totalEstimate @0
- 步骤3:验证totalConsumed属性属性totalConsumed @0
- 步骤4:验证totalLeft属性属性totalLeft @0
- 步骤5:验证progress属性属性progress @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r(get_class($productTest->getEmptyHourTest())) && p() && e('stdClass'); // 步骤1:验证返回对象类型
r($productTest->getEmptyHourTest()) && p('totalEstimate') && e('0'); // 步骤2:验证totalEstimate属性
r($productTest->getEmptyHourTest()) && p('totalConsumed') && e('0'); // 步骤3:验证totalConsumed属性
r($productTest->getEmptyHourTest()) && p('totalLeft') && e('0'); // 步骤4:验证totalLeft属性
r($productTest->getEmptyHourTest()) && p('progress') && e('0'); // 步骤5:验证progress属性