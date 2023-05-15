#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('module')->config('module')->gen(3);

/**

title=productModel->manageLine();
cid=1
pid=1

*/

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
