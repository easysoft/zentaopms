#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

zdTable('story')->gen(20);
$relation = zdTable('relation');
$relation->AID->range('1,11,2,12,3,13,4,14,5,15,6,16,7,17,8,18');
$relation->BID->range('11,1,12,2,13,3,14,4,15,5,16,6,17,7,18,8');
$relation->gen(16);

/**

title=测试 storyModel->batchGetRelations();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');

r($storyModel->batchGetRelations(array(), 'requirement')) && p() && e('0');

$group = $storyModel->batchGetRelations(array(1,2), 'requirement');
r((isset($group[1]['11']) and isset($group[2][12]))) && p() && e('1');
r($storyModel->batchGetRelations(array(9,10), 'requirement')) && p() && e('0');
$group = $storyModel->batchGetRelations(array(1,2,9,10), 'requirement');
r((isset($group[1]['11']) and isset($group[2][12]))) && p() && e('1');
$group = $storyModel->batchGetRelations(array(1,2), 'requirement', array('id', 'parent'));
r((isset($group[1]['11']->parent) and isset($group[2][12]->parent))) && p() && e('1');

$group = $storyModel->batchGetRelations(array(11,12), 'story');
r((isset($group[11]['1']) and isset($group[12][2]))) && p() && e('1');
r($storyModel->batchGetRelations(array(19,20), 'story')) && p() && e('0');
$group = $storyModel->batchGetRelations(array(11,12,9,10), 'story');
r((isset($group[11]['1']) and isset($group[12][2]))) && p() && e('1');
$group = $storyModel->batchGetRelations(array(11,12), 'story', array('id', 'parent'));
r((isset($group[11]['1']->parent) and isset($group[12][2]->parent))) && p() && e('1');
