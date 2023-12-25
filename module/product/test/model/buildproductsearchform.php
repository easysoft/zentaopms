#!/usr/bin/env php
<?php

/**

title=测试productModel->buildProductSearchForm();
cid=0

- 正确的queryID @1
- 错误的queryID @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(5);

$query = zdTable('userquery');
$query->id->range('1');
$query->account->range('admin');
$query->module->range('product');
$query->title->range('搜索条件1');
$query->form->range('`a:44:{s:9:"fieldname";s:0:"";s:7:"fieldid";s:0:"";s:11:"fieldstatus";s:1:"0";s:12:"fieldproject";s:1:"0";s:7:"fieldPM";s:0:"";s:13:"fieldopenedBy";s:0:"";s:15:"fieldopenedDate";s:0:"";s:10:"fieldbegin";s:0:"";s:8:"fieldend";s:0:"";s:14:"fieldrealBegan";s:0:"";s:12:"fieldrealEnd";s:0:"";s:13:"fieldclosedBy";s:0:"";s:19:"fieldlastEditedDate";s:0:"";s:15:"fieldclosedDate";s:0:"";s:14:"fieldteamCount";s:0:"";s:6:"andOr1";s:3:"AND";s:6:"field1";s:4:"name";s:9:"operator1";s:7:"include";s:6:"value1";s:6:"产品";s:6:"andOr2";s:3:"and";s:6:"field2";s:2:"id";s:9:"operator2";s:1:"=";s:6:"value2";s:0:"";s:6:"andOr3";s:3:"and";s:6:"field3";s:6:"status";s:9:"operator3";s:1:"=";s:6:"value3";s:1:"0";s:10:"groupAndOr";s:3:"and";s:6:"andOr4";s:3:"AND";s:6:"field4";s:7:"project";s:9:"operator4";s:1:"=";s:6:"value4";s:1:"0";s:6:"andOr5";s:3:"and";s:6:"field5";s:2:"PM";s:9:"operator5";s:1:"=";s:6:"value5";s:0:"";s:6:"andOr6";s:3:"and";s:6:"field6";s:8:"openedBy";s:9:"operator6";s:1:"=";s:6:"value6";s:0:"";s:6:"module";s:9:"product";s:9:"actionURL";s:50:"/product-all-bySearch-order_asc-0-myQueryID.html";s:10:"groupItems";s:1:"3";s:8:"formType";s:4:"lite";}`');
$query->sql->range("`(( 1   AND `name`  LIKE '%产品%' ) AND ( 1  ))`");
$query->gen(1);

$queryIDList = array('0', '1');

$product = new productTest('admin');
r($product->buildProductSearchFormTest($queryIDList[1])) && p() && e('1'); // 正确的queryID
r($product->buildProductSearchFormTest($queryIDList[0])) && p() && e('0'); // 错误的queryID
