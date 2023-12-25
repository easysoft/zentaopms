#!/usr/bin/env php
<?php

/**

title=测试productModel->deleteLine();
cid=0

- 测试删除产品线1
 - 属性name @产品线1
 - 属性deleted @1
- 测试删除产品线2
 - 属性name @产品线2
 - 属性deleted @1
- 测试删除产品线3
 - 属性name @产品线3
 - 属性deleted @1
- 测试删除产品线4
 - 属性name @产品线4
 - 属性deleted @1
- 测试删除产品线5
 - 属性name @产品线5
 - 属性deleted @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('user')->gen(5);
zdTable('product')->gen(10);
zdTable('module')->config('lines')->gen(30);

$lineIdList = range(1, 5);

$productTester = new productTest('admin');
r($productTester->deleteLineTest($lineIdList[0])) && p('name,deleted') && e('产品线1,1'); // 测试删除产品线1
r($productTester->deleteLineTest($lineIdList[1])) && p('name,deleted') && e('产品线2,1'); // 测试删除产品线2
r($productTester->deleteLineTest($lineIdList[2])) && p('name,deleted') && e('产品线3,1'); // 测试删除产品线3
r($productTester->deleteLineTest($lineIdList[3])) && p('name,deleted') && e('产品线4,1'); // 测试删除产品线4
r($productTester->deleteLineTest($lineIdList[4])) && p('name,deleted') && e('产品线5,1'); // 测试删除产品线5
