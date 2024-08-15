#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';
su('admin');

zenData('product')->gen(10);

/**

title=测试 dataviewModel::getColumns();
timeout=0
cid=1

- 获取产品表ID和name字段的类型。
 - 属性id @INT24
 - 属性name @VAR_STRING
- 获取BUG表ID和name字段的类型。
 - 属性id @INT24
 - 属性title @VAR_STRING

*/
$bi = new biTest();

r($bi->getColumns('select * from zt_product')) && p('id,name')  && e('INT24,VAR_STRING');  //获取产品表ID和name字段的类型。
r($bi->getColumns('select * from zt_bug')    ) && p('id,title') && e('INT24,VAR_STRING');  //获取BUG表ID和name字段的类型。