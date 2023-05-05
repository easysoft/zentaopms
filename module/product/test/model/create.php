#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(1);
zdTable('module')->gen(1);

/**

title=测试productModel->create();
cid=1
pid=1

*/

$product = new productTest('admin');

$create       = array('name' => 'case1', 'code' => 'testcase1');
$repeat       = array('name' => 'case1', 'code' => 'testcase1');
$namecode     = array('name' => 'case3', 'code' => 'testcase3');
$pronamecode  = array('program' => '3', 'name' => 'case4', 'code' => 'testcase4');
$intypestatus = array('program' => '4', 'name' => 'case5', 'code' => 'testcase5', 'type' => 'branch', 'status' => 'closed');

r($product->createObject($create))                && p('name')                && e('case1');                  // 测试正常的创建
r($product->createObject($repeat))                && p('code:0')              && e('『产品代号』已经有『testcase1』这条记录了。'); // 测试创建重复的产品
r($product->createObject($namecode, 'test line')) && p('name,code,line')      && e('case3,testcase3,2');        // 测试传入name和code
r($product->createObject($pronamecode))           && p('program,name,code')   && e('3,case4,testcase4');      // 测试传入program、name、code
r($product->createObject($intypestatus))          && p('program,type,status') && e('4,branch,closed');        // 测试传入program、name、code、type、status
