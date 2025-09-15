#!/usr/bin/env php
<?php

/**

title=测试 searchModel->processQueryFormDatas();
timeout=0
cid=1

- 测试 andOr,operator,value 的值
 -  @AND
 - 属性1 @include
 - 属性2 @测试
- 测试 andOr,operator,value 的值
 -  @OR
 - 属性1 @=
 - 属性2 @1
- 测试 andOr,operator,value 的值
 -  @AND
 - 属性1 @=
 - 属性2 @~~
- 测试 andOr,operator,value 的值
 -  @AND
 - 属性1 @=
 - 属性2 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$andOrName    = 'andOr1';
$operatorName = 'operator1';
$valueName    = 'value1';

$postDatas[] =[$andOrName => 'and', $operatorName => 'include', $valueName => '测试'];
$postDatas[] =[$andOrName => 'or',  $operatorName => '=',       $valueName => '1'];
$postDatas[] =[$andOrName => '',    $operatorName => ''];
$postDatas[] =[$andOrName => '',    $operatorName => '',        $valueName => 'ZERO'];

$fields = array('title', 'id', 'status', 'activatedCount');

$search = new searchTest();
r($search->processQueryFormDatasTest($postDatas[0], $fields[0], $andOrName, $operatorName, $valueName)) && p('0,1,2') && e('AND,include,测试'); // 测试 andOr,operator,value 的值
r($search->processQueryFormDatasTest($postDatas[1], $fields[1], $andOrName, $operatorName, $valueName)) && p('0,1,2') && e('OR,=,1');           // 测试 andOr,operator,value 的值
r($search->processQueryFormDatasTest($postDatas[2], $fields[2], $andOrName, $operatorName, $valueName)) && p('0,1,2') && e('AND,=,~~');         // 测试 andOr,operator,value 的值
r($search->processQueryFormDatasTest($postDatas[3], $fields[3], $andOrName, $operatorName, $valueName)) && p('0,1,2') && e('AND,=,0');          // 测试 andOr,operator,value 的值
