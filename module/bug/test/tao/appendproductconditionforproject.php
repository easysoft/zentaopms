#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

/**

title=bugTao->appendProductConditionForProject();
timeout=0
cid=15412

- 处理 查询语句 1=1 产品 1 分支 all 的查询语句 @1=1 AND `product` = 1
- 处理 查询语句 1=1 产品 2 分支 all 的查询语句 @1=1 AND `product` = 2
- 处理 查询语句 1=1 产品 1 分支 0   的查询语句 @1=1 AND `product` = 1 AND `branch` = 0
- 处理 查询语句 1=1 产品 2 分支 0   的查询语句 @1=1 AND `product` = 2 AND `branch` = 0
- 处理 查询语句 `product` != '0' 产品 1 分支 all 的查询语句 @`product` != '0'
- 处理 查询语句 `product` != '0' 产品 2 分支 all 的查询语句 @`product` != '0'
- 处理 查询语句 `product` != '0' 产品 1 分支 0   的查询语句 @`product` != '0'
- 处理 查询语句 `product` != '0' 产品 2 分支 0   的查询语句 @`product` != '0'

*/

$bugQuery      = array('1=1', "`product` != '0'");
$productIdList = array(1, 2);
$branch        = array('all', 0);

global $tester;
$bug = $tester->loadModel('bug');

r($bug->appendProductConditionForProject($bugQuery[0], $productIdList[0], $branch[0])) && p() && e("1=1 AND `product` = 1");                  // 处理 查询语句 1=1 产品 1 分支 all 的查询语句
r($bug->appendProductConditionForProject($bugQuery[0], $productIdList[1], $branch[0])) && p() && e("1=1 AND `product` = 2");                  // 处理 查询语句 1=1 产品 2 分支 all 的查询语句
r($bug->appendProductConditionForProject($bugQuery[0], $productIdList[0], $branch[1])) && p() && e("1=1 AND `product` = 1 AND `branch` = 0"); // 处理 查询语句 1=1 产品 1 分支 0   的查询语句
r($bug->appendProductConditionForProject($bugQuery[0], $productIdList[1], $branch[1])) && p() && e("1=1 AND `product` = 2 AND `branch` = 0"); // 处理 查询语句 1=1 产品 2 分支 0   的查询语句
r($bug->appendProductConditionForProject($bugQuery[1], $productIdList[0], $branch[0])) && p() && e("`product` != '0'"); // 处理 查询语句 `product` != '0' 产品 1 分支 all 的查询语句
r($bug->appendProductConditionForProject($bugQuery[1], $productIdList[1], $branch[0])) && p() && e("`product` != '0'"); // 处理 查询语句 `product` != '0' 产品 2 分支 all 的查询语句
r($bug->appendProductConditionForProject($bugQuery[1], $productIdList[0], $branch[1])) && p() && e("`product` != '0'"); // 处理 查询语句 `product` != '0' 产品 1 分支 0   的查询语句
r($bug->appendProductConditionForProject($bugQuery[1], $productIdList[1], $branch[1])) && p() && e("`product` != '0'"); // 处理 查询语句 `product` != '0' 产品 2 分支 0   的查询语句

unset($_SESSION['bugQuery']);
unset($_SESSION['storyBugQuery']);