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
$product->objectModel->app->moduleName = 'product';
$product->objectModel->app->methodName = 'browse';

r($product->getSwitcherTest(0))             && p('hasProduct,hasBranch') && e('1,0');
r($product->getSwitcherTest(1))             && p('hasProduct,hasBranch') && e('1,0');
r($product->getSwitcherTest(0, '',  'all')) && p('hasProduct,hasBranch') && e('1,0');
r($product->getSwitcherTest(41,'',  'all')) && p('hasProduct,hasBranch') && e('1,1');
r($product->getSwitcherTest(0, '',  0))     && p('hasProduct,hasBranch') && e('1,0');
r($product->getSwitcherTest(41,'',  0))     && p('hasProduct,hasBranch') && e('1,1');

$product->objectModel->app->viewType = 'mhtml';
r($product->getSwitcherTest(0))             && p('hasProduct,hasBranch') && e('1,0');
r($product->getSwitcherTest(1))             && p('hasProduct,hasBranch') && e('1,0');
r($product->getSwitcherTest(0, '',  'all')) && p('hasProduct,hasBranch') && e('1,0');
r($product->getSwitcherTest(41,'',  'all')) && p('hasProduct,hasBranch') && e('1,1');
r($product->getSwitcherTest(0, '',  0))     && p('hasProduct,hasBranch') && e('1,0');
r($product->getSwitcherTest(41,'',  0))     && p('hasProduct,hasBranch') && e('1,1');

