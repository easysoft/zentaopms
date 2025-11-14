#!/usr/bin/env php
<?php

/**

title=测试 screenModel::genChartData();
timeout=0
cid=18225

- 执行$result1->editCanvasConfig @1
- 执行$result2
 - 第editCanvasConfig条的width属性 @1300
 - 第editCanvasConfig条的height属性 @3267
- 执行$result3->componentList @1
- 执行$filter4
 - 属性year @2022
 - 属性month @3
 - 属性dept @1
 - 属性account @admin
- 执行$result5第editCanvasConfig条的width属性 @1920

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

su('admin');

$screenTest = new screenTest();

$testScreen1 = new stdClass();
$testScreen1->id = 1;
$testScreen1->scheme = '{"editCanvasConfig":{"width":1300,"height":3267},"componentList":[{"id":"test001","key":"TextCommon","isGroup":false}]}';
$testScreen1->builtin = 1;

$testScreen2 = new stdClass();
$testScreen2->id = 2;
$testScreen2->scheme = '{"editCanvasConfig":{"width":1300,"height":3267},"componentList":[{"id":"group001","isGroup":true,"groupList":[{"id":"sub001","key":"TextCommon","isGroup":false}]}]}';
$testScreen2->builtin = 1;

$testScreen3 = new stdClass();
$testScreen3->id = 3;
$testScreen3->scheme = '{"editCanvasConfig":{"width":1920,"height":1080},"componentList":[]}';
$testScreen3->builtin = 0;

list($result1, $filter1) = $screenTest->genChartDataTest($testScreen1, 0, 0, 0, '');
r(isset($result1->editCanvasConfig)) && p() && e('1');

list($result2, $filter2) = $screenTest->genChartDataTest($testScreen1, 0, 0, 0, '');
r($result2) && p('editCanvasConfig:width,height') && e('1300,3267');

list($result3, $filter3) = $screenTest->genChartDataTest($testScreen2, 0, 0, 0, '');
r(isset($result3->componentList)) && p() && e('1');

list($result4, $filter4) = $screenTest->genChartDataTest($testScreen1, 2022, 3, 1, 'admin');
r($filter4) && p('year,month,dept,account') && e('2022,3,1,admin');

list($result5, $filter5) = $screenTest->genChartDataTest($testScreen3, 2023, 0, 0, 'user');
r($result5) && p('editCanvasConfig:width') && e('1920');