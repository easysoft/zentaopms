#!/usr/bin/env php
<?php

/**

title=测试 productZen::responseNotFound4View();
timeout=0
cid=0

- 测试步骤1:Web模式下返回的响应结构包含result字段属性result @success
- 测试步骤2:Web模式下返回的load字段包含alert第load条的alert属性 @抱歉，您访问的对象不存在！
- 测试步骤3:Web模式下返回的load字段包含locate第load条的locate属性 @product-all.html
- 测试步骤4:API模式下返回的响应包含status字段属性status @fail
- 测试步骤5:API模式下返回的响应包含code字段属性code @404

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->responseNotFound4ViewTest('')) && p('result') && e('success'); // 测试步骤1:Web模式下返回的响应结构包含result字段
r($productTest->responseNotFound4ViewTest('')) && p('load:alert') && e('抱歉，您访问的对象不存在！'); // 测试步骤2:Web模式下返回的load字段包含alert
r($productTest->responseNotFound4ViewTest('')) && p('load:locate') && e('product-all.html'); // 测试步骤3:Web模式下返回的load字段包含locate
r($productTest->responseNotFound4ViewTest('api')) && p('status') && e('fail'); // 测试步骤4:API模式下返回的响应包含status字段
r($productTest->responseNotFound4ViewTest('api')) && p('code') && e('404'); // 测试步骤5:API模式下返回的响应包含code字段