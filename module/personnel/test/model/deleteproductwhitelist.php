#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('acl')->gen(100);
zdTable('product')->config('product')->gen(50);
zdTable('userview')->gen(50);
zdTable('user')->gen(20);

/**

title=测试 personnelModel->deleteProductWhitelist();
cid=1
pid=1

*/

$personnel = new personnelTest('admin');

$productID = array();
$productID[0] = 6;
$productID[1] = 8;
$productID[2] = 2;
$productID[3] = 111;

$account = array();
$account[0] = 'test6';
$account[1] = 'test33';
$account[2] = 'user6';

r($personnel->deleteProductWhitelistTest($productID[1], $account[0])) && p() && e(',6');  // 把 test6 从 产品 8 的白名单内删除 test6不在产品8的白名单内，所以不会删
r($personnel->deleteProductWhitelistTest($productID[0], $account[0])) && p() && e(',6');  // 把 test6 从 产品 6 的白名单内删除
r($personnel->deleteProductWhitelistTest($productID[2], $account[0])) && p() && e(',6'); // 把 test6 从 产品 2 的白名单内删除 产品2不是同步过来的，所以不会删
r($personnel->deleteProductWhitelistTest($productID[3], $account[0])) && p() && e(',6');  // 把 test6 从 产品 111 的白名单内删除 产品111不存在，所以不会删

r($personnel->deleteProductWhitelistTest($productID[0], $account[1])) && p() && e(',33'); // 把 test33 从 产品 6 的白名单内删除 test33不在产品6的白名单内，所以不会删
r($personnel->deleteProductWhitelistTest($productID[1], $account[1])) && p() && e(',33'); // 把 test33 从 产品 8 的白名单内删除
r($personnel->deleteProductWhitelistTest($productID[2], $account[1])) && p() && e(',33'); // 把 test33 从 产品 2 的白名单内删除 产品2不是同步过来的，所以不会删
r($personnel->deleteProductWhitelistTest($productID[3], $account[1])) && p() && e(',33'); // 把 test33 从 产品 111 的白名单内删除 产品111不存在，所以不会删

r($personnel->deleteProductWhitelistTest($productID[0], $account[2])) && p() && e('0'); // 把 user6 从 产品 6 的白名单内删除 user6在 userview 中不存在，所以不会删
r($personnel->deleteProductWhitelistTest($productID[1], $account[2])) && p() && e('0'); // 把 user6 从 产品 8 的白名单内删除 user6在 userview 中不存在，所以不会删
r($personnel->deleteProductWhitelistTest($productID[2], $account[2])) && p() && e('0'); // 把 user6 从 产品 2 的白名单内删除 user6在 userview 中不存在，所以不会删
r($personnel->deleteProductWhitelistTest($productID[3], $account[2])) && p() && e('0'); // 把 user6 从 产品 111 的白名单内删除 user6在 userview 中不存在，所以不会删
