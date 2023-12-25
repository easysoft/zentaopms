#!/usr/bin/env php
<?php

/**

title=测试productModel->setMenu();
cid=0

- 执行product模块的setMenuTest方法
 - 属性idReplaced @1
 - 属性branchReplaced @0
 - 属性hasBranch @1
 - 属性requirement @0
- 执行product模块的setMenuTest方法，参数是1
 - 属性idReplaced @1
 - 属性branchReplaced @0
 - 属性hasBranch @0
 - 属性requirement @0
- 执行product模块的setMenuTest方法，参数是0, '', 'requirement'
 - 属性idReplaced @1
 - 属性branchReplaced @0
 - 属性hasBranch @1
 - 属性requirement @1
- 执行product模块的setMenuTest方法，参数是1, '', 'requirement'
 - 属性idReplaced @1
 - 属性branchReplaced @0
 - 属性hasBranch @0
 - 属性requirement @1
- 执行product模块的setMenuTest方法，参数是0, 'all'
 - 属性idReplaced @1
 - 属性branchReplaced @0
 - 属性hasBranch @1
 - 属性requirement @0
- 执行product模块的setMenuTest方法，参数是41, 'all'
 - 属性idReplaced @1
 - 属性branchReplaced @1
 - 属性hasBranch @1
 - 属性requirement @0
- 执行product模块的setMenuTest方法，参数是0, 0, 'requirement'
 - 属性idReplaced @1
 - 属性branchReplaced @0
 - 属性hasBranch @1
 - 属性requirement @1
- 执行product模块的setMenuTest方法，参数是41, 0, 'requirement'
 - 属性idReplaced @1
 - 属性branchReplaced @1
 - 属性hasBranch @1
 - 属性requirement @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);

$product = new productTest('admin');

r($product->setMenuTest(0))                    && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,1,0');
r($product->setMenuTest(1))                    && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,0,0');
r($product->setMenuTest(0, '', 'requirement')) && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,1,1');
r($product->setMenuTest(1, '', 'requirement')) && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,0,1');
r($product->setMenuTest(0,  'all'))            && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,1,0');
r($product->setMenuTest(41, 'all'))            && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,1,1,0');
r($product->setMenuTest(0,  0, 'requirement')) && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,1,1');
r($product->setMenuTest(41, 0, 'requirement')) && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,1,1,1');
