#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->getByProducts();
cid=1
pid=1

测试获取产品 41 42 的分支信息 >> 41:,0,1,2;42:,0,3,4;1:,0;
测试获取产品 41 42 的分支信息 >> 41:,0,1,2;42:,0,3,4;1:,0;
测试获取产品 41 42 的分支信息 >> 41:,0,1;42:,0,3;1:,0;
测试获取产品 41 42 的分支信息 >> 41:,0,1,2;42:,0,3,4;
测试获取产品 41 42 的分支信息 >> 41:,1,2;42:,3,4;1:,0;
测试获取产品 41 42 的分支信息 >> 41:,0,1;42:,0,3;
测试获取产品 41 42 的分支信息 >> 41:,1,2;42:,3,4;
测试获取产品 41 42 追加branch20 21 的分支信息 >> 41:,0,1,2;42:,0,3,4;1:,0;
测试获取产品 41 42 noclosed 追加branch20 21 的分支信息 >> 41:,0,1;42:,0,3;1:,0;
测试获取产品 41 42 ignoreNormal 追加branch20 21 的分支信息 >> 41:,0,1,2;42:,0,3,4;
测试获取产品 41 42 noempty 追加branch20 21 的分支信息 >> 41:,1,2;42:,3,4;1:,0;
测试获取产品 41 42 noclosed,ignoreNormal 追加branch20 21 的分支信息 >> 41:,0,1;42:,0,3;
测试获取产品 41 42 ignoreNormal,noempty 追加branch20 21 的分支信息 >> 41:,1,2;42:,3,4;
测试获取产品 81 82 的分支信息 >> 81:,0,81,82;82:,0,83,84;2:,0;
测试获取产品 43 83 的分支信息 >> 43:,0,5,6;83:,0,85,86;

*/

$products = array('1,41,42', '2,81,82', '43,83');
$params   = array('', 'noclosed', 'ignoreNormal', 'noempty', 'noclosed,ignoreNormal', 'ignoreNormal,noempty');
$appendBranch = '';

$branch = new branchTest();

r($branch->getByProductsTest($products[0]))                            && p() && e('41:,0,1,2;42:,0,3,4;1:,0;');     // 测试获取产品 41 42 的分支信息
r($branch->getByProductsTest($products[0], $params[0]))                && p() && e('41:,0,1,2;42:,0,3,4;1:,0;');     // 测试获取产品 41 42 的分支信息
r($branch->getByProductsTest($products[0], $params[1]))                && p() && e('41:,0,1;42:,0,3;1:,0;');         // 测试获取产品 41 42 的分支信息
r($branch->getByProductsTest($products[0], $params[2]))                && p() && e('41:,0,1,2;42:,0,3,4;');          // 测试获取产品 41 42 的分支信息
r($branch->getByProductsTest($products[0], $params[3]))                && p() && e('41:,1,2;42:,3,4;1:,0;');         // 测试获取产品 41 42 的分支信息
r($branch->getByProductsTest($products[0], $params[4]))                && p() && e('41:,0,1;42:,0,3;');              // 测试获取产品 41 42 的分支信息
r($branch->getByProductsTest($products[0], $params[5]))                && p() && e('41:,1,2;42:,3,4;');              // 测试获取产品 41 42 的分支信息
r($branch->getByProductsTest($products[0], $params[0], $appendBranch)) && p() && e('41:,0,1,2;42:,0,3,4;1:,0;');     // 测试获取产品 41 42 追加branch20 21 的分支信息
r($branch->getByProductsTest($products[0], $params[1], $appendBranch)) && p() && e('41:,0,1;42:,0,3;1:,0;');         // 测试获取产品 41 42 noclosed 追加branch20 21 的分支信息
r($branch->getByProductsTest($products[0], $params[2], $appendBranch)) && p() && e('41:,0,1,2;42:,0,3,4;');          // 测试获取产品 41 42 ignoreNormal 追加branch20 21 的分支信息
r($branch->getByProductsTest($products[0], $params[3], $appendBranch)) && p() && e('41:,1,2;42:,3,4;1:,0;');         // 测试获取产品 41 42 noempty 追加branch20 21 的分支信息
r($branch->getByProductsTest($products[0], $params[4], $appendBranch)) && p() && e('41:,0,1;42:,0,3;');              // 测试获取产品 41 42 noclosed,ignoreNormal 追加branch20 21 的分支信息
r($branch->getByProductsTest($products[0], $params[5], $appendBranch)) && p() && e('41:,1,2;42:,3,4;');              // 测试获取产品 41 42 ignoreNormal,noempty 追加branch20 21 的分支信息
r($branch->getByProductsTest($products[1]))                            && p() && e('81:,0,81,82;82:,0,83,84;2:,0;'); // 测试获取产品 81 82 的分支信息
r($branch->getByProductsTest($products[2]))                            && p() && e('43:,0,5,6;83:,0,85,86;');        // 测试获取产品 43 83 的分支信息
