#!/usr/bin/env php
<?php

/**

title=productModel->concatProductLine();
cid=0

- 测试第一组产品列表的0条数据第0条的name属性 @产品线1/产品1
- 测试第一组产品列表的1条数据第1条的name属性 @产品线1/产品2
- 测试第一组产品列表的5条数据第5条的name属性 @产品线2/产品6
- 测试第一组产品列表的6条数据第6条的name属性 @产品线2/产品7
- 测试第一组产品列表的10条数据第10条的name属性 @产品线3/产品11
- 测试第一组产品列表的11条数据第11条的name属性 @产品线3/产品12
- 测试第二组产品列表的0条数据第0条的name属性 @产品线3/产品11
- 测试第二组产品列表的1条数据第1条的name属性 @产品线3/产品12
- 测试第二组产品列表的5条数据第5条的name属性 @产品线4/产品16
- 测试第二组产品列表的6条数据第6条的name属性 @产品线4/产品17
- 测试第二组产品列表的10条数据第10条的name属性 @产品线5/产品21
- 测试第二组产品列表的11条数据第11条的name属性 @产品线5/产品22
- 测试第三组产品列表的0条数据第0条的name属性 @产品线3/产品11
- 测试第三组产品列表的1条数据第1条的name属性 @产品线3/产品12
- 测试第三组产品列表的5条数据第5条的name属性 @产品线4/产品16
- 测试第三组产品列表的6条数据第6条的name属性 @产品线4/产品17
- 测试第三组产品列表的10条数据第10条的name属性 @产品26
- 测试第三组产品列表的11条数据第11条的name属性 @产品27

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('module')->config('line')->gen(5);
zdTable('product')->config('product')->gen(30);

$product = new productTest('admin');

$lineList = array(1, 2, 3, 4, 5);
$line1Products  = range(1, 5);
$line2Products  = range(6, 10);
$line3Products  = range(11, 15);
$line4Products  = range(16, 20);
$line5Products  = range(21, 25);
$noLineProducts = range(26,30);

$testSuit1 = array_merge($line1Products, $line2Products, $line3Products);
$testSuit2 = array_merge($line3Products, $line4Products, $line5Products);
$testSuit3 = array_merge($line3Products, $line4Products, $noLineProducts);

r($product->concatProductLineTest($testSuit1)) && p('0:name')  && e('产品线1/产品1');  // 测试第一组产品列表的0条数据
r($product->concatProductLineTest($testSuit1)) && p('1:name')  && e('产品线1/产品2');  // 测试第一组产品列表的1条数据
r($product->concatProductLineTest($testSuit1)) && p('5:name')  && e('产品线2/产品6');  // 测试第一组产品列表的5条数据
r($product->concatProductLineTest($testSuit1)) && p('6:name')  && e('产品线2/产品7');  // 测试第一组产品列表的6条数据
r($product->concatProductLineTest($testSuit1)) && p('10:name') && e('产品线3/产品11'); // 测试第一组产品列表的10条数据
r($product->concatProductLineTest($testSuit1)) && p('11:name') && e('产品线3/产品12'); // 测试第一组产品列表的11条数据

r($product->concatProductLineTest($testSuit2)) && p('0:name')  && e('产品线3/产品11'); // 测试第二组产品列表的0条数据
r($product->concatProductLineTest($testSuit2)) && p('1:name')  && e('产品线3/产品12'); // 测试第二组产品列表的1条数据
r($product->concatProductLineTest($testSuit2)) && p('5:name')  && e('产品线4/产品16'); // 测试第二组产品列表的5条数据
r($product->concatProductLineTest($testSuit2)) && p('6:name')  && e('产品线4/产品17'); // 测试第二组产品列表的6条数据
r($product->concatProductLineTest($testSuit2)) && p('10:name') && e('产品线5/产品21'); // 测试第二组产品列表的10条数据
r($product->concatProductLineTest($testSuit2)) && p('11:name') && e('产品线5/产品22'); // 测试第二组产品列表的11条数据

r($product->concatProductLineTest($testSuit3)) && p('0:name')  && e('产品线3/产品11'); // 测试第三组产品列表的0条数据
r($product->concatProductLineTest($testSuit3)) && p('1:name')  && e('产品线3/产品12'); // 测试第三组产品列表的1条数据
r($product->concatProductLineTest($testSuit3)) && p('5:name')  && e('产品线4/产品16'); // 测试第三组产品列表的5条数据
r($product->concatProductLineTest($testSuit3)) && p('6:name')  && e('产品线4/产品17'); // 测试第三组产品列表的6条数据
r($product->concatProductLineTest($testSuit3)) && p('10:name') && e('产品26');         // 测试第三组产品列表的10条数据
r($product->concatProductLineTest($testSuit3)) && p('11:name') && e('产品27');         // 测试第三组产品列表的11条数据
