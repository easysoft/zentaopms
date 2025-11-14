#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('branch')->loadYaml('branch')->gen(30);
zenData('user')->gen(5);
su('admin');

/**

title=测试 branchModel->getByProducts();
timeout=0
cid=15325

- 获取空产品列表下的分支数据 @0
- 获取产品列表下的分支数据 @6:|0|1|2|3;7:|0|4|5|6;8:|0|7|8|9;9:|0|10|11|12;10:|0|13|14|15;1:|0;2:|0;
- 获取不存在产品列表下的分支数据 @0
- 获取空产品列表下的未关闭分支数据 @0
- 获取产品列表下的未关闭分支数据 @6:|0|1|2;7:|0|4|5;8:|0|7|8;9:|0|10|11;10:|0|13|14;1:|0;2:|0;
- 获取不存在产品列表下的未关闭分支数据 @0
- 获取空产品列表下的忽略正常分支数据 @0
- 获取产品列表下的忽略正常分支数据 @6:|0|1|2|3;7:|0|4|5|6;8:|0|7|8|9;9:|0|10|11|12;10:|0|13|14|15;
- 获取不存在产品列表下的忽略正常分支数据 @0
- 获取空产品列表下的非主干分支数据 @0
- 获取产品列表下的非主干分支数据 @6:|1|2|3;7:|4|5|6;8:|7|8|9;9:|10|11|12;10:|13|14|15;1:|0;2:|0;
- 获取不存在产品列表下的非主干分支数据 @0
- 获取空产品列表下的未关闭且忽略正常分支数据 @0
- 获取产品列表下的未关闭且忽略正常分支数据 @6:|0|1|2;7:|0|4|5;8:|0|7|8;9:|0|10|11;10:|0|13|14;
- 获取不存在产品列表下的未关闭且忽略正常分支数据 @0
- 获取空产品列表下的未关闭且忽略正常且非主干分支数据 @0
- 获取产品列表下的未关闭且忽略正常且非主干分支数据 @6:|1|2|3;7:|4|5|6;8:|7|8|9;9:|10|11|12;10:|13|14|15;
- 获取不存在产品列表下的未关闭且忽略正常且非主干分支数据 @0
- 获取追加分支20和21和空产品列表下的分支数据 @0
- 获取追加分支20和21和产品列表下的分支数据 @6:|0|1|2|3;7:|0|4|5|6;8:|0|7|8|9;9:|0|10|11|12;10:|0|13|14|15;1:|0;2:|0;
- 获取追加分支20和21和不存在产品列表下的分支数据 @0
- 获取追加分支20和21和空产品列表下的未关闭分支数据 @0
- 获取追加分支20和21和产品列表下的未关闭分支数据 @6:|0|1|2;7:|0|4|5;8:|0|7|8;9:|0|10|11;10:|0|13|14;1:|0;2:|0;
- 获取追加分支20和21和不存在产品列表下的未关闭分支数据 @0
- 获取追加分支20和21和空产品列表下的忽略正常分支数据 @0
- 获取追加分支20和21和产品列表下的忽略正常分支数据 @6:|0|1|2|3;7:|0|4|5|6;8:|0|7|8|9;9:|0|10|11|12;10:|0|13|14|15;
- 获取追加分支20和21和不存在产品列表下的忽略正常分支数据 @0
- 获取追加分支20和21和空产品列表下的非主干分支数据 @0
- 获取追加分支20和21和产品列表下的非主干分支数据 @6:|1|2|3;7:|4|5|6;8:|7|8|9;9:|10|11|12;10:|13|14|15;1:|0;2:|0;
- 获取追加分支20和21和不存在产品列表下的非主干分支数据 @0
- 获取追加分支20和21和空产品列表下的未关闭且忽略正常分支数据 @0
- 获取追加分支20和21和产品列表下的未关闭且忽略正常分支数据 @6:|0|1|2;7:|0|4|5;8:|0|7|8;9:|0|10|11;10:|0|13|14;
- 获取追加分支20和21和不存在产品列表下的未关闭且忽略正常分支数据 @0
- 获取追加分支20和21和空产品列表下的未关闭且忽略正常且非主干分支数据 @0
- 获取追加分支20和21和产品列表下的未关闭且忽略正常且非主干分支数据 @6:|1|2|3;7:|4|5|6;8:|7|8|9;9:|10|11|12;10:|13|14|15;
- 获取不存在产20和21和品列表下的未关闭且忽略正常且非主干分支数据 @0

*/

$productIdList[0] = array();
$productIdList[1] = range(1, 10);
$productIdList[2] = range(11, 20);

$paramList        = array('', 'noclosed', 'ignoreNormal', 'noempty', 'noclosed,ignoreNormal', 'ignoreNormal,noempty');

$appendBranchList[0] = array();
$appendBranchList[1] = array(20, 21);

$branchTester = new branchTest();
r($branchTester->getByProductsTest($productIdList[0], $paramList[0], $appendBranchList[0])) && p() && e('0');                                                                        // 获取空产品列表下的分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[0], $appendBranchList[0])) && p() && e('6:|0|1|2|3;7:|0|4|5|6;8:|0|7|8|9;9:|0|10|11|12;10:|0|13|14|15;1:|0;2:|0;'); // 获取产品列表下的分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[0], $appendBranchList[0])) && p() && e('0');                                                                        // 获取不存在产品列表下的分支数据
r($branchTester->getByProductsTest($productIdList[0], $paramList[1], $appendBranchList[0])) && p() && e('0');                                                                        // 获取空产品列表下的未关闭分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[1], $appendBranchList[0])) && p() && e('6:|0|1|2;7:|0|4|5;8:|0|7|8;9:|0|10|11;10:|0|13|14;1:|0;2:|0;');             // 获取产品列表下的未关闭分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[1], $appendBranchList[0])) && p() && e('0');                                                                        // 获取不存在产品列表下的未关闭分支数据
r($branchTester->getByProductsTest($productIdList[0], $paramList[2], $appendBranchList[0])) && p() && e('0');                                                                        // 获取空产品列表下的忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[2], $appendBranchList[0])) && p() && e('6:|0|1|2|3;7:|0|4|5|6;8:|0|7|8|9;9:|0|10|11|12;10:|0|13|14|15;');           // 获取产品列表下的忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[2], $appendBranchList[0])) && p() && e('0');                                                                        // 获取不存在产品列表下的忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[0], $paramList[3], $appendBranchList[0])) && p() && e('0');                                                                        // 获取空产品列表下的非主干分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[3], $appendBranchList[0])) && p() && e('6:|1|2|3;7:|4|5|6;8:|7|8|9;9:|10|11|12;10:|13|14|15;1:|0;2:|0;');           // 获取产品列表下的非主干分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[3], $appendBranchList[0])) && p() && e('0');                                                                        // 获取不存在产品列表下的非主干分支数据
r($branchTester->getByProductsTest($productIdList[0], $paramList[4], $appendBranchList[0])) && p() && e('0');                                                                        // 获取空产品列表下的未关闭且忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[4], $appendBranchList[0])) && p() && e('6:|0|1|2;7:|0|4|5;8:|0|7|8;9:|0|10|11;10:|0|13|14;');                       // 获取产品列表下的未关闭且忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[4], $appendBranchList[0])) && p() && e('0');                                                                        // 获取不存在产品列表下的未关闭且忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[0], $paramList[5], $appendBranchList[0])) && p() && e('0');                                                                        // 获取空产品列表下的未关闭且忽略正常且非主干分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[5], $appendBranchList[0])) && p() && e('6:|1|2|3;7:|4|5|6;8:|7|8|9;9:|10|11|12;10:|13|14|15;');                     // 获取产品列表下的未关闭且忽略正常且非主干分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[5], $appendBranchList[0])) && p() && e('0');                                                                        // 获取不存在产品列表下的未关闭且忽略正常且非主干分支数据

r($branchTester->getByProductsTest($productIdList[0], $paramList[0], $appendBranchList[1])) && p() && e('0');                                                                        // 获取追加分支20和21和空产品列表下的分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[0], $appendBranchList[1])) && p() && e('6:|0|1|2|3;7:|0|4|5|6;8:|0|7|8|9;9:|0|10|11|12;10:|0|13|14|15;1:|0;2:|0;'); // 获取追加分支20和21和产品列表下的分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[0], $appendBranchList[1])) && p() && e('0');                                                                        // 获取追加分支20和21和不存在产品列表下的分支数据
r($branchTester->getByProductsTest($productIdList[0], $paramList[1], $appendBranchList[1])) && p() && e('0');                                                                        // 获取追加分支20和21和空产品列表下的未关闭分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[1], $appendBranchList[1])) && p() && e('6:|0|1|2;7:|0|4|5;8:|0|7|8;9:|0|10|11;10:|0|13|14;1:|0;2:|0;');             // 获取追加分支20和21和产品列表下的未关闭分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[1], $appendBranchList[1])) && p() && e('0');                                                                        // 获取追加分支20和21和不存在产品列表下的未关闭分支数据
r($branchTester->getByProductsTest($productIdList[0], $paramList[2], $appendBranchList[1])) && p() && e('0');                                                                        // 获取追加分支20和21和空产品列表下的忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[2], $appendBranchList[1])) && p() && e('6:|0|1|2|3;7:|0|4|5|6;8:|0|7|8|9;9:|0|10|11|12;10:|0|13|14|15;');           // 获取追加分支20和21和产品列表下的忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[2], $appendBranchList[1])) && p() && e('0');                                                                        // 获取追加分支20和21和不存在产品列表下的忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[0], $paramList[3], $appendBranchList[1])) && p() && e('0');                                                                        // 获取追加分支20和21和空产品列表下的非主干分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[3], $appendBranchList[1])) && p() && e('6:|1|2|3;7:|4|5|6;8:|7|8|9;9:|10|11|12;10:|13|14|15;1:|0;2:|0;');           // 获取追加分支20和21和产品列表下的非主干分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[3], $appendBranchList[1])) && p() && e('0');                                                                        // 获取追加分支20和21和不存在产品列表下的非主干分支数据
r($branchTester->getByProductsTest($productIdList[0], $paramList[4], $appendBranchList[1])) && p() && e('0');                                                                        // 获取追加分支20和21和空产品列表下的未关闭且忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[4], $appendBranchList[1])) && p() && e('6:|0|1|2;7:|0|4|5;8:|0|7|8;9:|0|10|11;10:|0|13|14;');                       // 获取追加分支20和21和产品列表下的未关闭且忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[4], $appendBranchList[1])) && p() && e('0');                                                                        // 获取追加分支20和21和不存在产品列表下的未关闭且忽略正常分支数据
r($branchTester->getByProductsTest($productIdList[0], $paramList[5], $appendBranchList[1])) && p() && e('0');                                                                        // 获取追加分支20和21和空产品列表下的未关闭且忽略正常且非主干分支数据
r($branchTester->getByProductsTest($productIdList[1], $paramList[5], $appendBranchList[1])) && p() && e('6:|1|2|3;7:|4|5|6;8:|7|8|9;9:|10|11|12;10:|13|14|15;');                     // 获取追加分支20和21和产品列表下的未关闭且忽略正常且非主干分支数据
r($branchTester->getByProductsTest($productIdList[2], $paramList[5], $appendBranchList[1])) && p() && e('0');                                                                        // 获取不存在产20和21和品列表下的未关闭且忽略正常且非主干分支数据
