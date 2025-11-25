#!/usr/bin/env php
<?php

/**

title=测试 cneModel::cneServerError();
timeout=0
cid=15608

- 测试步骤1:正常情况下返回CNE服务器错误对象属性code @600
- 测试步骤2:正常情况下返回CNE服务器错误对象属性message @CNE服务器出错
- 测试步骤3:验证返回对象的数据类型 @object
- 测试步骤4:验证返回对象包含code属性 @1
- 测试步骤5:验证返回对象包含message属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->cneServerErrorTest()) && p('code') && e('600'); // 测试步骤1:正常情况下返回CNE服务器错误对象
r($cneTest->cneServerErrorTest()) && p('message') && e('CNE服务器出错'); // 测试步骤2:正常情况下返回CNE服务器错误对象
r(gettype($cneTest->cneServerErrorTest())) && p() && e('object'); // 测试步骤3:验证返回对象的数据类型
r(property_exists($cneTest->cneServerErrorTest(), 'code')) && p() && e('1'); // 测试步骤4:验证返回对象包含code属性
r(property_exists($cneTest->cneServerErrorTest(), 'message')) && p() && e('1'); // 测试步骤5:验证返回对象包含message属性