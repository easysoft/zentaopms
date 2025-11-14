#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildProductForActivate();
timeout=0
cid=17563

- 步骤1:正常激活产品属性status @normal
- 步骤2:OR视觉模式下激活产品属性status @normal
- 步骤3:验证返回的对象类型 @1
- 步骤4:不存在的产品ID属性status @normal
- 步骤5:激活产品的状态字段验证属性status @normal

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(10);
zenData('user')->gen(5);

su('admin');

$productTest = new productZenTest();

r($productTest->buildProductForActivateTest(1)) && p('status') && e('normal'); // 步骤1:正常激活产品
r($productTest->buildProductForActivateTest(2)) && p('status') && e('normal'); // 步骤2:OR视觉模式下激活产品
r(is_object($productTest->buildProductForActivateTest(3))) && p() && e('1'); // 步骤3:验证返回的对象类型
r($productTest->buildProductForActivateTest(999)) && p('status') && e('normal'); // 步骤4:不存在的产品ID
r($productTest->buildProductForActivateTest(4)) && p('status') && e('normal'); // 步骤5:激活产品的状态字段验证