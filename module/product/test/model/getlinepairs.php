#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

function initData()
{
    $module = zdTable('module');
    $module->id->range('1-1000');
    $module->root->range('1-5');  //产品线所属项目集
    $module->name->prefix("产品线")->range('1-1000');
    $module->type->range("line");
    $module->parent->range('`0`');

    $module->gen(10);
}
initData();

/**

title=productModel->getLinePairs();
cid=1
pid=1

*/
$product = new productTest('admin');

r($product->getLinePairsTest(-1))       && p()      && e('0');                 // 测试获取程序集-1的信息
r(count($product->getLinePairsTest(0))) && p()      && e('10');                // 获取所有未删除的产品线
r($product->getLinePairsTest(1))        && p('1,6') && e('产品线1,产品线6');   // 测试获取程序集1的信息
r($product->getLinePairsTest(2))        && p('2,7') && e('产品线2,产品线7');   // 测试获取程序集2的信息
r($product->getLinePairsTest(3))        && p('3,8') && e('产品线3,产品线8');   // 测试获取程序集3的信息
r($product->getLinePairsTest(4))        && p('4,9') && e('产品线4,产品线9');   // 测试获取程序集4的信息
r($product->getLinePairsTest(10001))    && p()      && e('0');                 // 测试获取不存在程序集的信息
