#!/usr/bin/env php
<?php

/**

title=productModel->manageLine();
cid=0

- 不传入数据。
 - 第1条的root属性 @1
 - 第1条的name属性 @产品线1
- 查看修改后的结果，比较 id = 1 的结果。
 - 属性root @1
 - 属性name @新产品线1
- 查看修改后的结果，比较 id = 2 的结果。
 - 属性root @2
 - 属性name @新产品线2
- 查看修改后的结果，比较 id = 3 的结果。
 - 属性root @3
 - 属性name @新产品线3
- 查看修改后的结果，比较 id = 4 的结果。
 - 属性root @1
 - 属性name @新产品线4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('module')->config('module')->gen(3);

$product = new productTest('admin');

$lines = array();
$lines[1]['id1'] = '新产品线1';
$lines[2]['id2'] = '新产品线2';
$lines[3]['id3'] = '新产品线3';
$lines[1][0]     = '新产品线4';
r($product->manageLineTest(array())) && p('1:root,name') && e('1,产品线1');  //不传入数据。

$newLines = $product->manageLineTest($lines);
r(array_pop($newLines)) && p('root,name') && e('1,新产品线1'); //查看修改后的结果，比较 id = 1 的结果。
r(array_pop($newLines)) && p('root,name') && e('2,新产品线2'); //查看修改后的结果，比较 id = 2 的结果。
r(array_pop($newLines)) && p('root,name') && e('3,新产品线3'); //查看修改后的结果，比较 id = 3 的结果。
r(array_pop($newLines)) && p('root,name') && e('1,新产品线4'); //查看修改后的结果，比较 id = 4 的结果。
