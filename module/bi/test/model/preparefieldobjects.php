#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareFieldObjects();
timeout=0
cid=15204

- 测试步骤1: 验证返回结果是数组类型 @array
- 测试步骤2: 验证返回的第一个元素text为产品第0条的text属性 @产品
- 测试步骤3: 验证返回的第一个元素value为product第0条的value属性 @product
- 测试步骤4: 验证返回结果包含多个对象(至少5个) @1
- 测试步骤5: 验证story对象在结果中存在 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

r(gettype($biTest->prepareFieldObjectsTest())) && p() && e('array'); // 测试步骤1: 验证返回结果是数组类型
r($biTest->prepareFieldObjectsTest()) && p('0:text') && e('产品'); // 测试步骤2: 验证返回的第一个元素text为产品
r($biTest->prepareFieldObjectsTest()) && p('0:value') && e('product'); // 测试步骤3: 验证返回的第一个元素value为product
r(count($biTest->prepareFieldObjectsTest()) >= 5 ? 1 : 0) && p() && e('1'); // 测试步骤4: 验证返回结果包含多个对象(至少5个)
r(in_array('story', array_column($biTest->prepareFieldObjectsTest(), 'value')) ? 1 : 0) && p() && e('1'); // 测试步骤5: 验证story对象在结果中存在