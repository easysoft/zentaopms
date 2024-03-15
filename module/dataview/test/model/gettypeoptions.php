#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 dataviewModel::getTypeOptions();
timeout=0
cid=1

- 获取bug模块的字段列表。
 - 第id条的name属性 @Bug编号
 - 第title条的name属性 @Bug标题
- 获取bug模块的字段列表。
 - 第id条的name属性 @编号
 - 第name条的name属性 @产品名称

*/
global $tester;
$tester->loadModel('dataview');

r($tester->dataview->getTypeOptions('bug'))     && p('id:name;title:name') && e('Bug编号,Bug标题'); //获取bug模块的字段列表。
r($tester->dataview->getTypeOptions('product')) && p('id:name;name:name')  && e('编号,产品名称');   //获取bug模块的字段列表。
