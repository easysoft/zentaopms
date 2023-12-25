#!/usr/bin/env php
<?php

/**

title=productModel->createLine();
cid=0

- 执行product模块的createLineTest方法，参数是'1', 'test line1'
 - 属性root @1
 - 属性name @test line1
- 执行product模块的createLineTest方法，参数是'0', 'test line1'  @0
- 执行product模块的createLineTest方法，参数是'1', ''  @0
- 执行product模块的createLineTest方法，参数是'1', "<script>alert
 - 属性root @1
 - 属性name @&lt;script&gt;alert(&#039;test&#039;)&lt;/script&gt;

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('module')->gen(1);

$product = new productTest('admin');

r($product->createLineTest('1', 'test line1')) && p('root,name') && e('1,test line1');
r($product->createLineTest('0', 'test line1')) && p() && e('0');
r($product->createLineTest('1', '')) && p() && e('0');
r($product->createLineTest('1', "<script>alert('test')</script>")) && p('root,name') && e('1,&lt;script&gt;alert(&#039;test&#039;)&lt;/script&gt;');
