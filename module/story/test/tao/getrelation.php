#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

$relation = zdTable('relation');
$relation->AID->range('1,11,2,12,3,13,4,14,5,15,6,16,7,17,8,18');
$relation->BID->range('11,1,12,2,13,3,14,4,15,5,16,6,17,7,18,8');
$relation->gen(16);

/**

title=测试 storyModel->getRelation();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');

r($storyModel->getRelation(0, 'requirement')) && p() && e('0');

r(key($storyModel->getRelation(1, 'requirement'))) && p() && e('11');
r($storyModel->getRelation(9, 'requirement')) && p() && e('0');

r(key($storyModel->getRelation(11, 'story'))) && p() && e('1');
r($storyModel->getRelation(19, 'story')) && p() && e('0');
