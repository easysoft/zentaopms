#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processQueryFormDatas();
timeout=0
cid=0

- 测试正常POST数据处理
 - 属性1 @AND
 - 属性2 @include
 - 属性3 @测试
- 测试OR逻辑处理
 - 属性1 @OR
 - 属性2 @=
 - 属性3 @1
- 测试空值处理
 - 属性1 @AND
 - 属性2 @=
 - 属性3 @~~
- 测试ZERO特殊值处理
 - 属性1 @AND
 - 属性2 @=
 - 属性3 @0
- 测试无效andOr值处理
 - 属性1 @AND
 - 属性2 @!=
 - 属性3 @test

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$andOrName    = 'andOr1';
$operatorName = 'operator1';
$valueName    = 'value1';

$postDatas[] = array($andOrName => 'and', $operatorName => 'include', $valueName => '测试');
$postDatas[] = array($andOrName => 'or',  $operatorName => '=',       $valueName => '1');
$postDatas[] = array($andOrName => '',    $operatorName => '',        $valueName => '');
$postDatas[] = array($andOrName => '',    $operatorName => '',        $valueName => 'ZERO');
$postDatas[] = array($andOrName => 'invalid', $operatorName => '!=',    $valueName => 'test');

$fields = array('title', 'id', 'status', 'activatedCount', 'name');
$fieldParams = array();

$search = new searchTest();
r($search->processQueryFormDatasTest($postDatas[0], $fields[0], $andOrName, $operatorName, $valueName)) && p('0,1,2') && e('AND,include,测试'); // 测试正常POST数据处理
r($search->processQueryFormDatasTest($postDatas[1], $fields[1], $andOrName, $operatorName, $valueName)) && p('0,1,2') && e('OR,=,1');           // 测试OR逻辑处理
r($search->processQueryFormDatasTest($postDatas[2], $fields[2], $andOrName, $operatorName, $valueName)) && p('0,1,2') && e('AND,=,~~');         // 测试空值处理
r($search->processQueryFormDatasTest($postDatas[3], $fields[3], $andOrName, $operatorName, $valueName)) && p('0,1,2') && e('AND,=,0');          // 测试ZERO特殊值处理
r($search->processQueryFormDatasTest($postDatas[4], $fields[4], $andOrName, $operatorName, $valueName)) && p('0,1,2') && e('AND,!=,test');      // 测试无效andOr值处理
