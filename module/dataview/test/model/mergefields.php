#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 dataviewModel::mergeFields();
timeout=0
cid=15959

- 测试合并字段。
 - 第0条的id属性 @Bug编号
 - 第0条的title属性 @Bug标题
 - 第1条的id属性 @bug
 - 第1条的title属性 @bug
- 测试多个表合并字段。
 - 第0条的id属性 @编号
 - 第0条的title属性 @title
 - 第1条的id属性 @product
 - 第1条的title属性 @product

*/
global $tester;
$tester->loadModel('dataview');

$dataFields  = array('id', 'title');
$sqlFields   = array('zt_bug.id', 'zt_bug.title');
$moduleNames = array('zt_bug' => 'bug');
r($tester->dataview->mergeFields($dataFields, $sqlFields, $moduleNames)) && p('0:id;0:title;1:id;1:title')  && e('Bug编号,Bug标题,bug,bug');  //测试合并字段。

$sqlFields   = array('zt_product.id', 'zt_bug.title');
$moduleNames = array('zt_bug' => 'bug', 'zt_product' => 'product');
r($tester->dataview->mergeFields($dataFields, $sqlFields, $moduleNames)) && p('0:id;0:title;1:id;1:title')  && e('编号,title,product,product');  //测试多个表合并字段。
