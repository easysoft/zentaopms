#!/usr/bin/env php
<?php

/**

title=测试 apiModel::getGroupedObjects();
timeout=0
cid=15108

- 步骤1：获取分组对象数据包含product键 @1
- 步骤2：获取分组对象数据包含project键 @1
- 步骤3：获取分组对象数据包含nolink键 @1
- 步骤4：测试无关联API库的数量 @0
- 步骤5：验证返回数据是数组类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->gen(5);
zenData('project')->gen(5);
zenData('doclib')->gen(10);

su('admin');

$apiTest = new apiModelTest();

r(array_key_exists('product', $apiTest->getGroupedObjectsTest())) && p() && e('1'); // 步骤1：获取分组对象数据包含product键
r(array_key_exists('project', $apiTest->getGroupedObjectsTest())) && p() && e('1'); // 步骤2：获取分组对象数据包含project键
r(array_key_exists('nolink', $apiTest->getGroupedObjectsTest())) && p() && e('1'); // 步骤3：获取分组对象数据包含nolink键
r(count($apiTest->getGroupedObjectsTest()['nolink'])) && p() && e('0'); // 步骤4：测试无关联API库的数量
r(is_array($apiTest->getGroupedObjectsTest())) && p() && e('1'); // 步骤5：验证返回数据是数组类型