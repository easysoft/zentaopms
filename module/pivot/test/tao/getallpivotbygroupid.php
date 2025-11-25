#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getAllPivotByGroupID();
timeout=0
cid=17439

- 执行pivotTest模块的getAllPivotByGroupIDTest方法，参数是60  @2
- 执行pivotTest模块的getAllPivotByGroupIDTest方法，参数是999  @0
- 执行pivotTest模块的getAllPivotByGroupIDTest方法  @0
- 执行pivotTest模块的getAllPivotByGroupIDTest方法，参数是-1  @0
- 执行pivotTest模块的getAllPivotByGroupIDTest方法，参数是60
 - 第0条的id属性 @1002
 - 第0条的0:name属性 @透视表2详细信息

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

zenData('company')->loadYaml('company', false, 2)->gen(5);
zenData('config')->loadYaml('config', false, 4)->gen(300);

$pivotTable = zenData('pivot');
$pivotTable->id->range('1001-1003');
$pivotTable->dimension->range('1');
$pivotTable->group->range('60{2},70{1}');
$pivotTable->name->range('透视表1详细信息,透视表2详细信息,草稿透视表');
$pivotTable->stage->range('published{2},draft{1}');
$pivotTable->deleted->range('0{2},1{1}');
$pivotTable->gen(3);

$specTable = zenData('pivotspec');
$specTable->pivot->range('1001-1003');
$specTable->version->range('1');
$specTable->name->range('透视表1详细信息,透视表2详细信息,草稿透视表');
$specTable->gen(3);

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->getAllPivotByGroupIDTest(60)) && p() && e('2');
r($pivotTest->getAllPivotByGroupIDTest(999)) && p() && e('0');
r($pivotTest->getAllPivotByGroupIDTest(0)) && p() && e('0');
r($pivotTest->getAllPivotByGroupIDTest(-1)) && p() && e('0');
r($pivotTest->getAllPivotByGroupIDTest(60)) && p('0:id,0:name') && e('1002,透视表2详细信息');