#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 upgradeModel->updatePivotStage();
timeout=0
cid=1

- 测试1008数据修改是否正确 @1
- 测试1012数据修改是否正确 @1
- 测试1015数据修改是否正确 @1
- 测试1020数据修改是否正确 @1
- 测试1027数据修改是否正确 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->gen(5);
su('admin');

$changeFields = array();
$changeFields[1008] = array();
$changeFields[1008]['fields'] = '"execution":{"object":"project","field":"execution","type":"string"},"type":{"object":"project","field":"type","type":"string"}';

$changeFields[1012] = array();
$changeFields[1012]['fields'] = '"project":{"object":"projectstory","field":"project","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"status":{"object":"project","field":"status","type":"string"}';

$changeFields[1013] = array();
$changeFields[1013]['fields'] = '"execution":{"object":"project","field":"execution","type":"string"},"stage":{"object":"project","field":"stage","type":"string"}}';

$changeFields[1015] = array();
$changeFields[1015]['fields'] = '"bugID":{"object":"bug","field":"bugID","type":"string"},"status":{"object":"project","field":"status","type":"string"}';

$changeFields[1020] = array();
$changeFields[1020]['fields'] = '"bugID":{"object":"project","field":"bugID","type":"string"},"type":{"object":"product","field":"type","type":"string"}';

$changeFields[1027] = array();
$changeFields[1027]['fields'] = '"bugID":{"object":"bug","field":"bugID","type":"string"},"type":{"object":"project","field":"type","type":"string"}';

$rightValue = array();
$rightValue[1008] = array();
$rightValue[1008]['fields'] = '{"id":{"object":"project","field":"id","type":"string"},"project":{"object":"project","field":"project","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"type":{"object":"task","field":"type","type":"option"},"taskID":{"object":"task","field":"taskID","type":"string"},"projectstatus":{"object":"task","field":"projectstatus","type":"string"}}';
$rightValue[1008]['langs']  = '{"project":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"execution":{"zh-cn":"\\u6267\\u884c\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76eeID","zh-tw":"","en":"","de":"","fr":""},"type":{"zh-cn":"\\u4efb\\u52a1\\u7c7b\\u578b","zh-tw":"","en":"","de":"","fr":""},"taskID":{"zh-cn":"taskID","zh-tw":"","en":"","de":"","fr":""},"projectstatus":{"zh-cn":"projectstatus","zh-tw":"","en":"","de":"","fr":""}}';

$rightValue[1012] = array();
$rightValue[1012]['fields'] = '{"id":{"object":"project","field":"id","type":"string"},"project":{"object":"projectstory","field":"project","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"status":{"object":"story","field":"status","type":"option"}}';

$rightValue[1013] = array();
$rightValue[1013]['fields'] = '{"id":{"object":"project","field":"id","type":"string"},"project":{"object":"projectstory","field":"project","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"stage":{"object":"story","field":"stage","type":"option"}}';
$rightValue[1013]['langs']  = '{"project":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"execution":{"zh-cn":"\\u6267\\u884c\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76eeID","zh-tw":"","en":"","de":"","fr":""},"stage":{"zh-cn":"\\u9636\\u6bb5","zh-tw":"","en":"","de":"","fr":""}}';

$rightValue[1015] = array();
$rightValue[1015]['fields'] = '{"id":{"object":"project","field":"id","type":"string"},"project":{"object":"project","field":"project","type":"string"},"t3id":{"object":"project","field":"t3id","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"bugID":{"object":"bug","field":"bugID","type":"string"},"status":{"object":"bug","field":"status","type":"option"}}';

$rightValue[1020] = array();
$rightValue[1020]['fields'] = '{"id":{"object":"product","field":"id","type":"string"},"name":{"object":"product","field":"name","type":"string"},"bugID":{"object":"project","field":"bugID","type":"string"},"type":{"object":"bug","field":"type","type":"option"}}';
$rightValue[1020]['langs']  = '{"count":{"zh-cn":"\\u9700\\u6c42\\u6570","zh-tw":"\\u9700\\u6c42\\u6570","en":"Stories"},"done":{"zh-cn":"\\u5b8c\\u6210\\u6570","zh-tw":"\\u5b8c\\u6210\\u6570","en":"Done"},"id":{"zh-cn":"\\u7f16\\u53f7","zh-tw":"","en":"","de":"","fr":""},"name":{"zh-cn":"\\u4ea7\\u54c1\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"bugID":{"zh-cn":"bugID","zh-tw":"","en":"","de":"","fr":""},"type":{"zh-cn":"Bug\\u7c7b\\u578b","zh-tw":"","en":"","de":"","fr":""}}';

$rightValue[1027] = array();
$rightValue[1027]['fields'] = '{"id":{"object":"project","field":"id","type":"string"},"project":{"object":"project","field":"project","type":"string"},"t3id":{"object":"project","field":"t3id","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"bugID":{"object":"bug","field":"bugID","type":"string"},"type":{"object":"bug","field":"type","type":"option"}}';
$rightValue[1027]['langs']  = '{"stories":{"zh-cn":"\\u9700\\u6c42\\u6570","zh-tw":"\\u9700\\u6c42\\u6570","en":"Stories"},"tasks":{"zh-cn":"\\u4efb\\u52a1\\u6570","zh-tw":"\\u4efb\\u52a1\\u6570","en":"Tasks"},"undoneStory":{"zh-cn":"\\u5269\\u4f59\\u9700\\u6c42\\u6570","zh-tw":"\\u5269\\u4f59\\u9700\\u6c42\\u6570","en":"Undone Story"},"undoneTask":{"zh-cn":"\\u5269\\u4f59\\u4efb\\u52a1\\u6570","zh-tw":"\\u5269\\u4f59\\u4efb\\u52a1\\u6570","en":"Undone Task"},"consumed":{"zh-cn":"\\u5df2\\u6d88\\u8017\\u5de5\\u65f6","zh-tw":"\\u5df2\\u6d88\\u8017\\u5de5\\u65f6","en":"Cost(h)"},"left":{"zh-cn":"\\u5269\\u4f59\\u5de5\\u65f6","zh-tw":"\\u5269\\u4f59\\u5de5\\u65f6","en":"Left(h)"},"consumedPercent":{"zh-cn":"\\u8fdb\\u5ea6","zh-tw":"\\u8fdb\\u5ea6","en":"Process"},"execution":{"zh-cn":"\\u6267\\u884c\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76eeID","zh-tw":"","en":"","de":"","fr":""},"project":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"bugID":{"zh-cn":"bugID","zh-tw":"","en":"","de":"","fr":""},"type":{"zh-cn":"Bug\\u7c7b\\u578b","zh-tw":"","en":"","de":"","fr":""}}';

$upgrade = new upgradeTest();
r($upgrade->updatePivotFieldsTypeTest(1008, $changeFields[1008], $rightValue)) && p() && e('1'); //测试1008数据修改是否正确
r($upgrade->updatePivotFieldsTypeTest(1012, $changeFields[1012], $rightValue)) && p() && e('1'); //测试1012数据修改是否正确
r($upgrade->updatePivotFieldsTypeTest(1015, $changeFields[1015], $rightValue)) && p() && e('1'); //测试1015数据修改是否正确
r($upgrade->updatePivotFieldsTypeTest(1020, $changeFields[1020], $rightValue)) && p() && e('1'); //测试1020数据修改是否正确
r($upgrade->updatePivotFieldsTypeTest(1027, $changeFields[1027], $rightValue)) && p() && e('1'); //测试1027数据修改是否正确
