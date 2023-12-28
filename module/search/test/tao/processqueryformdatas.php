#!/usr/bin/env php
<?php

/**

title=测试 searchModel->processQueryFormDatas();
timeout=0
cid=1

- 测试 andOr,operator,value 的值
 - 属性andOr @AND
 - 属性operator @include
 - 属性value @测试
- 测试 andOr,operator,value 的值
 - 属性andOr @OR
 - 属性operator @=
 - 属性value @1
- 测试 andOr,operator,value 的值
 - 属性andOr @AND
 - 属性operator @=
 - 属性value @~~
- 测试 andOr,operator,value 的值
 - 属性andOr @AND
 - 属性operator @=
 - 属性value @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';

su('admin');

$fields       = array('title', 'id', 'status', 'activatedCount');
$andOrName    = 'andOr1';
$operatorName = 'operator1';
$valueName    = 'value1';

$postDatas = array();
$postData1 = new stdClass();
$postData1->$andOrName    = 'and';
$postData1->$operatorName = 'include';
$postData1->$valueName    = '测试';

$postData2 = new stdClass();
$postData2->$andOrName    = 'or';
$postData2->$operatorName = '=';
$postData2->$valueName    = '1';

$postData3 = new stdClass();
$postData3->$andOrName    = '';
$postData3->$operatorName = '';

$postData4 = new stdClass();
$postData4->$andOrName    = '';
$postData4->$operatorName = '';
$postData4->$valueName    = 'ZERO';

$postDatas[] = $postData1;
$postDatas[] = $postData2;
$postDatas[] = $postData3;
$postDatas[] = $postData4;

$search = new searchTest();
r($search->processQueryFormDatasTest($postDatas[0], $fields[0], $andOrName, $operatorName, $valueName)) && p('andOr,operator,value') && e('AND,include,测试'); //测试 andOr,operator,value 的值
r($search->processQueryFormDatasTest($postDatas[1], $fields[1], $andOrName, $operatorName, $valueName)) && p('andOr,operator,value') && e('OR,=,1'); //测试 andOr,operator,value 的值
r($search->processQueryFormDatasTest($postDatas[2], $fields[2], $andOrName, $operatorName, $valueName)) && p('andOr,operator,value') && e('AND,=,~~'); //测试 andOr,operator,value 的值
r($search->processQueryFormDatasTest($postDatas[3], $fields[3], $andOrName, $operatorName, $valueName)) && p('andOr,operator,value') && e('AND,=,0'); //测试 andOr,operator,value 的值
