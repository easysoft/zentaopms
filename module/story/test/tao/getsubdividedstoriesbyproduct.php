#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

$story = zdTable('story');
$story->product->range(7);
$story->gen(20);

$relation = zdTable('relation');
$relation->product->range(7);
$relation->AID->range('1,11,2,12,3,13,4,14,5,15,6,16,7,17,8,18');
$relation->BID->range('11,1,12,2,13,3,14,4,15,5,16,6,17,7,18,8');
$relation->gen(16);

/**

title=测试 storyModel->getSubdividedStoriesByProduct();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');
$storyModel->config->URAndSR = 1;

r($storyModel->getSubdividedStoriesByProduct(0)) && p() && e('0');

r(count($storyModel->getSubdividedStoriesByProduct(7))) && p() && e('4');

$storyModel->dao->update(TABLE_STORY)->set('deleted')->eq(1)->where('id')->eq(1)->exec();
$storyModel->dao::$cache = array();
r(count($storyModel->getSubdividedStoriesByProduct(7))) && p() && e('3');

$storyModel->config->URAndSR = 0;
r($storyModel->getSubdividedStoriesByProduct(7)) && p() && e('0');
