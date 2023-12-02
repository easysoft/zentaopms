#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('product')->gen(5);

/**

title=bugTao->processSearchQuery();
timeout=0
cid=1

*/

$object        = array('bug', 'story');
$productIdList = array(1, 2);
$branch        = array('all', 0);

global $tester;
$tester->session->set('bugQuery', '`product` != \'0\'');
$tester->session->set('storyBugQuery', '`product` != \'0\'');

$bug = $tester->loadModel('bug');

r($bug->processSearchQuery($object[0], 0, array($productIdList[0]), $branch[0])) && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5)");                          // 处理 bug 产品 1 分支 all 的查询语句
r($bug->processSearchQuery($object[0], 0, array($productIdList[1]), $branch[0])) && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5)");                          // 处理 bug 产品 2 分支 all 的查询语句
r($bug->processSearchQuery($object[0], 0, array($productIdList[0]), $branch[1])) && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5) AND `branch` in('0','0')"); // 处理 bug 产品 1 分支 0   的查询语句
r($bug->processSearchQuery($object[0], 0, array($productIdList[1]), $branch[1])) && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5) AND `branch` in('0','0')"); // 处理 bug 产品 2 分支 0   的查询语句
r($bug->processSearchQuery($object[1], 0, $productIdList[0], $branch[0]))        && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5)");                          // 处理 story 产品 1 分支 all 的查询语句
r($bug->processSearchQuery($object[1], 0, $productIdList[1], $branch[0]))        && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5)");                          // 处理 story 产品 2 分支 all 的查询语句
r($bug->processSearchQuery($object[1], 0, $productIdList[0], $branch[1]))        && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5) AND `branch` in('0','0')"); // 处理 story 产品 1 分支 0   的查询语句
r($bug->processSearchQuery($object[1], 0, $productIdList[1], $branch[1]))        && p() && e("`product` != '0' AND `product` IN (1,2,3,4,5) AND `branch` in('0','0')"); // 处理 story 产品 2 分支 0   的查询语句

unset($_SESSION['bugQuery']);
unset($_SESSION['storyBugQuery']);
