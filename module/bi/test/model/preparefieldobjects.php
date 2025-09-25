#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareFieldObjects();
timeout=0
cid=0

- 步骤1：测试返回数组不为空 @1
- 步骤2：测试第一个对象的text属性第0条的text属性 @产品
- 步骤3：测试第一个对象的value属性第0条的value属性 @product
- 步骤4：测试第二个对象的text属性第1条的text属性 @软件需求
- 步骤5：测试返回数组长度大于5个对象 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

r(count($biTest->prepareFieldObjectsTest()) > 0) && p() && e('1');             // 步骤1：测试返回数组不为空
r($biTest->prepareFieldObjectsTest()) && p('0:text') && e('产品');             // 步骤2：测试第一个对象的text属性
r($biTest->prepareFieldObjectsTest()) && p('0:value') && e('product');         // 步骤3：测试第一个对象的value属性
r($biTest->prepareFieldObjectsTest()) && p('1:text') && e('软件需求');          // 步骤4：测试第二个对象的text属性
r(count($biTest->prepareFieldObjectsTest()) >= 5) && p() && e('1');           // 步骤5：测试返回数组长度大于5个对象