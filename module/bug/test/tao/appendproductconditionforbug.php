#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('product')->gen(5);

/**

title=bugTao->appendProductConditionForBug();
timeout=0
cid=1

*/

$bugQuery      = array('1=1', "`product` != '0'");
$productIdList = array(1, 2);
$branch        = array('all', '0');

global $tester;
$bug = $tester->loadModel('bug');

r($bug->appendProductConditionForBug($bugQuery[0], array($productIdList[0]), $branch[0])) && p() && e("1=1 AND `product` IN (1)");                  // 处理 查询语句 1=1 产品 1 分支 all 的查询语句
r($bug->appendProductConditionForBug($bugQuery[0], array($productIdList[1]), $branch[0])) && p() && e("1=1 AND `product` IN (2)");                  // 处理 查询语句 1=1 产品 2 分支 all 的查询语句
r($bug->appendProductConditionForBug($bugQuery[0], array($productIdList[0]), $branch[1])) && p() && e("1=1 AND `product` IN (1) AND `branch` in('0','0')"); // 处理 查询语句 1=1 产品 1 分支 0   的查询语句
r($bug->appendProductConditionForBug($bugQuery[0], array($productIdList[1]), $branch[1])) && p() && e("1=1 AND `product` IN (2) AND `branch` in('0','0')"); // 处理 查询语句 1=1 产品 2 分支 0   的查询语句
r($bug->appendProductConditionForBug($bugQuery[1], array($productIdList[0]), $branch[0])) && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5)"); // 处理 查询语句 `product` != '0' 产品 1 分支 all 的查询语句
r($bug->appendProductConditionForBug($bugQuery[1], array($productIdList[1]), $branch[0])) && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5)"); // 处理 查询语句 `product` != '0' 产品 2 分支 all 的查询语句
r($bug->appendProductConditionForBug($bugQuery[1], array($productIdList[0]), $branch[1])) && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5) AND `branch` in('0','0')"); // 处理 查询语句 `product` != '0' 产品 1 分支 0   的查询语句
r($bug->appendProductConditionForBug($bugQuery[1], array($productIdList[1]), $branch[1])) && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5) AND `branch` in('0','0')"); // 处理 查询语句 `product` != '0' 产品 2 分支 0   的查询语句

unset($_SESSION['bugQuery']);
unset($_SESSION['storyBugQuery']);
