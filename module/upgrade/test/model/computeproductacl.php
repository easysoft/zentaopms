#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->computeProductAcl();
cid=19506

- 测试更新产品 1 2 3 的项目集ID改为 0 lineID改为 0 @1:0,private,0; 2:0,open,0; 3:1,private,2;

- 测试更新产品 1 2 3 的项目集ID改为 0 lineID改为 1 @1:0,private,1; 2:0,open,1; 3:1,private,2;

- 测试更新产品 1 2 3 的项目集ID改为 0 lineID改为 null @1:0,private,1; 2:0,open,1; 3:1,private,2;

- 测试更新产品 1 2 3 的项目集ID改为 1 lineID改为 0 @1:1,private,0; 2:1,open,0; 3:1,private,2;

- 测试更新产品 1 2 3 的项目集ID改为 1 lineID改为 1 @1:1,private,0; 2:1,open,0; 3:1,private,2;

- 测试更新产品 1 2 3 的项目集ID改为 1 lineID改为 null @1:1,private,0; 2:1,open,0; 3:1,private,2;

- 测试更新产品 1 2 3 的项目集ID改为 2 lineID改为 0 @1:1,private,0; 2:1,open,0; 3:1,private,2;

- 测试更新产品 1 2 3 的项目集ID改为 2 lineID改为 1 @1:1,private,0; 2:1,open,0; 3:1,private,2;

- 测试更新产品 1 2 3 的项目集ID改为 2 lineID改为 null @1:1,private,0; 2:1,open,0; 3:1,private,2;

- 测试更新产品 4 5 6 的项目集ID改为 0 lineID改为 0 @4:0,private,0; 5:0,open,0; 6:1,private,5;

- 测试更新产品 4 5 6 的项目集ID改为 0 lineID改为 1 @4:0,private,1; 5:0,open,1; 6:1,private,5;

- 测试更新产品 4 5 6 的项目集ID改为 0 lineID改为 null @4:0,private,1; 5:0,open,1; 6:1,private,5;

- 测试更新产品 4 5 6 的项目集ID改为 1 lineID改为 0 @4:1,private,0; 5:1,open,0; 6:1,private,5;

- 测试更新产品 4 5 6 的项目集ID改为 1 lineID改为 1 @4:1,private,0; 5:1,open,0; 6:1,private,5;

- 测试更新产品 4 5 6 的项目集ID改为 1 lineID改为 null @4:1,private,0; 5:1,open,0; 6:1,private,5;

- 测试更新产品 4 5 6 的项目集ID改为 2 lineID改为 0 @4:1,private,0; 5:1,open,0; 6:1,private,5;

- 测试更新产品 4 5 6 的项目集ID改为 2 lineID改为 1 @4:1,private,0; 5:1,open,0; 6:1,private,5;

- 测试更新产品 4 5 6 的项目集ID改为 2 lineID改为 null @4:1,private,0; 5:1,open,0; 6:1,private,5;

- 测试更新产品 7 8 9 的项目集ID改为 0 lineID改为 null @7:0,private,6; 8:0,open,7; 9:1,private,8;

- 测试更新产品 7 8 9 的项目集ID改为 0 lineID改为 1 @7:0,private,1; 8:0,open,1; 9:1,private,8;

- 测试更新产品 7 8 9 的项目集ID改为 1 lineID改为 null @7:1,private,1; 8:1,open,1; 9:1,private,8;

- 测试更新产品 7 8 9 的项目集ID改为 1 lineID改为 1 @7:1,private,1; 8:1,open,1; 9:1,private,8;

- 测试更新产品 7 8 9 的项目集ID改为 2 lineID改为 null @7:1,private,1; 8:1,open,1; 9:1,private,8;

- 测试更新产品 7 8 9 的项目集ID改为 2 lineID改为 1 @7:1,private,1; 8:1,open,1; 9:1,private,8;

- 测试更新不存在的产品 10 11 12 的项目集ID改为 0 lineID改为 0 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

zenData('user')->gen(5);

$product = zenData('product');
$product->program->range('0{2},1');
$product->acl->range('custom,open,private');
$product->gen(9);

su('admin');

$upgrade = new upgradeTest();

$productIdList = array(array(1, 2, 3), array(4, 5, 6), array(7, 8, 9), array(10, 11, 12));
$programID     = array(0, 1, 2);
$lineID        = array(0, 1, null);

r($upgrade->computeProductAclTest($productIdList[0], $programID[0], $lineID[0])) && p() && e('1:0,private,0; 2:0,open,0; 3:1,private,2;');  // 测试更新产品 1 2 3 的项目集ID改为 0 lineID改为 0
r($upgrade->computeProductAclTest($productIdList[0], $programID[0], $lineID[1])) && p() && e('1:0,private,1; 2:0,open,1; 3:1,private,2;');  // 测试更新产品 1 2 3 的项目集ID改为 0 lineID改为 1
r($upgrade->computeProductAclTest($productIdList[0], $programID[0], $lineID[2])) && p() && e('1:0,private,1; 2:0,open,1; 3:1,private,2;');  // 测试更新产品 1 2 3 的项目集ID改为 0 lineID改为 null
r($upgrade->computeProductAclTest($productIdList[0], $programID[1], $lineID[0])) && p() && e('1:1,private,0; 2:1,open,0; 3:1,private,2;');  // 测试更新产品 1 2 3 的项目集ID改为 1 lineID改为 0
r($upgrade->computeProductAclTest($productIdList[0], $programID[1], $lineID[1])) && p() && e('1:1,private,0; 2:1,open,0; 3:1,private,2;');  // 测试更新产品 1 2 3 的项目集ID改为 1 lineID改为 1
r($upgrade->computeProductAclTest($productIdList[0], $programID[1], $lineID[2])) && p() && e('1:1,private,0; 2:1,open,0; 3:1,private,2;');  // 测试更新产品 1 2 3 的项目集ID改为 1 lineID改为 null
r($upgrade->computeProductAclTest($productIdList[0], $programID[2], $lineID[0])) && p() && e('1:1,private,0; 2:1,open,0; 3:1,private,2;');  // 测试更新产品 1 2 3 的项目集ID改为 2 lineID改为 0
r($upgrade->computeProductAclTest($productIdList[0], $programID[2], $lineID[1])) && p() && e('1:1,private,0; 2:1,open,0; 3:1,private,2;');  // 测试更新产品 1 2 3 的项目集ID改为 2 lineID改为 1
r($upgrade->computeProductAclTest($productIdList[0], $programID[2], $lineID[2])) && p() && e('1:1,private,0; 2:1,open,0; 3:1,private,2;');  // 测试更新产品 1 2 3 的项目集ID改为 2 lineID改为 null

r($upgrade->computeProductAclTest($productIdList[1], $programID[0], $lineID[0])) && p() && e('4:0,private,0; 5:0,open,0; 6:1,private,5;');  // 测试更新产品 4 5 6 的项目集ID改为 0 lineID改为 0
r($upgrade->computeProductAclTest($productIdList[1], $programID[0], $lineID[1])) && p() && e('4:0,private,1; 5:0,open,1; 6:1,private,5;');  // 测试更新产品 4 5 6 的项目集ID改为 0 lineID改为 1
r($upgrade->computeProductAclTest($productIdList[1], $programID[0], $lineID[2])) && p() && e('4:0,private,1; 5:0,open,1; 6:1,private,5;');  // 测试更新产品 4 5 6 的项目集ID改为 0 lineID改为 null
r($upgrade->computeProductAclTest($productIdList[1], $programID[1], $lineID[0])) && p() && e('4:1,private,0; 5:1,open,0; 6:1,private,5;');  // 测试更新产品 4 5 6 的项目集ID改为 1 lineID改为 0
r($upgrade->computeProductAclTest($productIdList[1], $programID[1], $lineID[1])) && p() && e('4:1,private,0; 5:1,open,0; 6:1,private,5;');  // 测试更新产品 4 5 6 的项目集ID改为 1 lineID改为 1
r($upgrade->computeProductAclTest($productIdList[1], $programID[1], $lineID[2])) && p() && e('4:1,private,0; 5:1,open,0; 6:1,private,5;');  // 测试更新产品 4 5 6 的项目集ID改为 1 lineID改为 null
r($upgrade->computeProductAclTest($productIdList[1], $programID[2], $lineID[0])) && p() && e('4:1,private,0; 5:1,open,0; 6:1,private,5;');  // 测试更新产品 4 5 6 的项目集ID改为 2 lineID改为 0
r($upgrade->computeProductAclTest($productIdList[1], $programID[2], $lineID[1])) && p() && e('4:1,private,0; 5:1,open,0; 6:1,private,5;');  // 测试更新产品 4 5 6 的项目集ID改为 2 lineID改为 1
r($upgrade->computeProductAclTest($productIdList[1], $programID[2], $lineID[2])) && p() && e('4:1,private,0; 5:1,open,0; 6:1,private,5;');  // 测试更新产品 4 5 6 的项目集ID改为 2 lineID改为 null

r($upgrade->computeProductAclTest($productIdList[2], $programID[0], $lineID[2])) && p() && e('7:0,private,6; 8:0,open,7; 9:1,private,8;');  // 测试更新产品 7 8 9 的项目集ID改为 0 lineID改为 null
r($upgrade->computeProductAclTest($productIdList[2], $programID[0], $lineID[1])) && p() && e('7:0,private,1; 8:0,open,1; 9:1,private,8;');  // 测试更新产品 7 8 9 的项目集ID改为 0 lineID改为 1
r($upgrade->computeProductAclTest($productIdList[2], $programID[1], $lineID[2])) && p() && e('7:1,private,1; 8:1,open,1; 9:1,private,8;');  // 测试更新产品 7 8 9 的项目集ID改为 1 lineID改为 null
r($upgrade->computeProductAclTest($productIdList[2], $programID[1], $lineID[1])) && p() && e('7:1,private,1; 8:1,open,1; 9:1,private,8;');  // 测试更新产品 7 8 9 的项目集ID改为 1 lineID改为 1
r($upgrade->computeProductAclTest($productIdList[2], $programID[2], $lineID[2])) && p() && e('7:1,private,1; 8:1,open,1; 9:1,private,8;');  // 测试更新产品 7 8 9 的项目集ID改为 2 lineID改为 null
r($upgrade->computeProductAclTest($productIdList[2], $programID[2], $lineID[1])) && p() && e('7:1,private,1; 8:1,open,1; 9:1,private,8;');  // 测试更新产品 7 8 9 的项目集ID改为 2 lineID改为 1

r($upgrade->computeProductAclTest($productIdList[3], $programID[0], $lineID[0])) && p() && e('0');  // 测试更新不存在的产品 10 11 12 的项目集ID改为 0 lineID改为 0
