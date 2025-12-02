#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 dataviewModel::getTypeOptions();
timeout=0
cid=15956

- 步骤1：获取bug模块的字段信息
 - 第id条的name属性 @Bug编号
 - 第title条的name属性 @Bug标题
- 步骤2：获取product模块的字段信息
 - 第id条的name属性 @编号
 - 第name条的name属性 @产品名称
- 步骤3：测试不存在的模块 @0
- 步骤4：测试空字符串输入 @0
- 步骤5：获取user模块的字段信息
 - 第id条的name属性 @用户编号
 - 第dept条的name属性 @部门

*/
global $tester;
$tester->loadModel('dataview');

r($tester->dataview->getTypeOptions('bug')) && p('id:name;title:name') && e('Bug编号,Bug标题'); //步骤1：获取bug模块的字段信息
r($tester->dataview->getTypeOptions('product')) && p('id:name;name:name') && e('编号,产品名称'); //步骤2：获取product模块的字段信息
r($tester->dataview->getTypeOptions('nonexistent')) && p() && e('0'); //步骤3：测试不存在的模块
r($tester->dataview->getTypeOptions('')) && p() && e('0'); //步骤4：测试空字符串输入
r($tester->dataview->getTypeOptions('user')) && p('id:name;dept:name') && e('用户编号,部门'); //步骤5：获取user模块的字段信息