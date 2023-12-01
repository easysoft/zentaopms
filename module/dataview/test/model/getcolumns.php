#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->gen(10);

/**

title=测试 dataviewModel::getColumns();
timeout=0
cid=1

- 获取产品表ID和name字段的类型。
 - 属性id @number
 - 属性name @string
- 获取BUG表ID和name字段的类型。
 - 属性id @number
 - 属性title @string

*/
global $tester;
$tester->loadModel('dataview');

r($tester->dataview->getColumns('select * from zt_product')) && p('id,name')  && e('number,string');  //获取产品表ID和name字段的类型。
r($tester->dataview->getColumns('select * from zt_bug')    ) && p('id,title') && e('number,string');  //获取BUG表ID和name字段的类型。
