#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 dataviewModel::isClickable();
timeout=0
cid=15958

- 测试create操作可点击验证 @1
- 测试edit操作可点击验证 @1
- 测试空action操作可点击验证 @1
- 测试数字action操作可点击验证 @1
- 测试特殊字符action操作可点击验证 @1

*/

su('admin');

$dataviewTest = new dataviewModelTest();

$normalDataview = new stdclass();
$normalDataview->id = 1;
$normalDataview->name = 'test_dataview';

$emptyDataview = new stdclass();

r($dataviewTest->isClickableTest($normalDataview, 'create')) && p() && e('1');  //测试create操作可点击验证
r($dataviewTest->isClickableTest($normalDataview, 'edit')) && p() && e('1');    //测试edit操作可点击验证
r($dataviewTest->isClickableTest($emptyDataview, '')) && p() && e('1');         //测试空action操作可点击验证
r($dataviewTest->isClickableTest($normalDataview, '123')) && p() && e('1');     //测试数字action操作可点击验证
r($dataviewTest->isClickableTest($normalDataview, '@#$%')) && p() && e('1');    //测试特殊字符action操作可点击验证