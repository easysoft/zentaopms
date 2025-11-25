#!/usr/bin/env php
<?php

/**

title=测试 devModel::getFields();
timeout=0
cid=16003

- 步骤1：正常情况获取用户表字段信息第id条的name属性 @用户编号
- 步骤2：varchar类型字段解析测试第name条的type属性 @varchar
- 步骤3：字段名国际化处理测试第begin条的name属性 @开始
- 步骤4：字段null属性检查测试第account条的null属性 @NO
- 步骤5：int类型字段解析测试第id条的type属性 @int
- 步骤6：char类型字段解析测试第account条的type属性 @char
- 步骤7：产品表字段名称解析测试第name条的name属性 @产品名称
- 步骤8：不存在表的异常处理测试 @0
- 步骤9：空表名边界值测试 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

su('admin');

$dev = new devTest();

r($dev->getFieldsTest('zt_user')) && p('id:name') && e('用户编号'); // 步骤1：正常情况获取用户表字段信息
r($dev->getFieldsTest('zt_product')) && p('name:type') && e('varchar'); // 步骤2：varchar类型字段解析测试
r($dev->getFieldsTest('zt_todo')) && p('begin:name') && e('开始'); // 步骤3：字段名国际化处理测试
r($dev->getFieldsTest('zt_user')) && p('account:null') && e('NO'); // 步骤4：字段null属性检查测试
r($dev->getFieldsTest('zt_user')) && p('id:type') && e('int'); // 步骤5：int类型字段解析测试
r($dev->getFieldsTest('zt_user')) && p('account:type') && e('char'); // 步骤6：char类型字段解析测试
r($dev->getFieldsTest('zt_product')) && p('name:name') && e('产品名称'); // 步骤7：产品表字段名称解析测试
r($dev->getFieldsTest('zt_nonexistent_table')) && p() && e('0'); // 步骤8：不存在表的异常处理测试
r($dev->getFieldsTest('')) && p() && e('0'); // 步骤9：空表名边界值测试