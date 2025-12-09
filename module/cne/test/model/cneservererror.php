#!/usr/bin/env php
<?php

/**

title=测试 cneModel::cneServerError();
timeout=0
cid=15608

- 步骤1:验证返回对象code属性值为600属性code @600
- 步骤2:验证返回对象message属性值属性message @CNE服务器出错
- 步骤3:验证返回对象同时包含正确的code和message
 - 属性code @600
 - 属性message @CNE服务器出错
- 步骤4:验证返回对象类型为stdClass @stdClass
- 步骤5:验证返回对象同时包含code和message属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

r($cneTest->cneServerErrorTest()) && p('code') && e('600'); // 步骤1:验证返回对象code属性值为600
r($cneTest->cneServerErrorTest()) && p('message') && e('CNE服务器出错'); // 步骤2:验证返回对象message属性值
r($cneTest->cneServerErrorTest()) && p('code,message') && e('600,CNE服务器出错'); // 步骤3:验证返回对象同时包含正确的code和message
r(get_class($cneTest->cneServerErrorTest())) && p() && e('stdClass'); // 步骤4:验证返回对象类型为stdClass
r(property_exists($cneTest->cneServerErrorTest(), 'code') && property_exists($cneTest->cneServerErrorTest(), 'message')) && p() && e('1'); // 步骤5:验证返回对象同时包含code和message属性