#!/usr/bin/env php
<?php

/**

title=测试 devModel::getFields();
timeout=0
cid=0

- 步骤1：获取正常表用户字段信息第id条的name属性 @用户编号
- 步骤2：测试varchar类型字段解析第name条的type属性 @varchar
- 步骤3：测试字段名国际化处理第begin条的name属性 @开始
- 步骤4：测试字段null属性处理第account条的null属性 @NO
- 步骤5：测试int类型字段解析第id条的type属性 @int
- 步骤6：测试字符类型字段解析第account条的type属性 @char
- 步骤7：测试字段名称解析第name条的name属性 @产品名称

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

su('admin');

$dev = new devTest();

r($dev->getFieldsTest('zt_user')) && p('id:name') && e('用户编号'); // 步骤1：获取正常表用户字段信息
r($dev->getFieldsTest('zt_product')) && p('name:type') && e('varchar'); // 步骤2：测试varchar类型字段解析
r($dev->getFieldsTest('zt_todo')) && p('begin:name') && e('开始'); // 步骤3：测试字段名国际化处理
r($dev->getFieldsTest('zt_user')) && p('account:null') && e('NO'); // 步骤4：测试字段null属性处理
r($dev->getFieldsTest('zt_user')) && p('id:type') && e('int'); // 步骤5：测试int类型字段解析
r($dev->getFieldsTest('zt_user')) && p('account:type') && e('char'); // 步骤6：测试字符类型字段解析
r($dev->getFieldsTest('zt_product')) && p('name:name') && e('产品名称'); // 步骤7：测试字段名称解析