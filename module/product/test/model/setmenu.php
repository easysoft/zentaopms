#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);

/**

title=测试productModel->setMenu();
timeout=0
cid=1

*/

$product = new productTest('admin');

r($product->setMenuTest(0))                    && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,1,0');
r($product->setMenuTest(1))                    && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,0,0');
r($product->setMenuTest(0, '', 'requirement')) && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,1,1');
r($product->setMenuTest(1, '', 'requirement')) && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,0,1');
r($product->setMenuTest(0,  'all'))            && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,1,0');
r($product->setMenuTest(41, 'all'))            && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,1,1,0');
r($product->setMenuTest(0,  0, 'requirement')) && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,0,1,1');
r($product->setMenuTest(41, 0, 'requirement')) && p('idReplaced,branchReplaced,hasBranch,requirement') && e('1,1,1,1');
