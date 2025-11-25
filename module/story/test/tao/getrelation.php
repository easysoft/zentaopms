#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getRelation();
timeout=0
cid=18644

- 执行storyModel模块的getRelation方法，参数是0, 'requirement'  @0
- 执行storyModel模块的getRelation方法，参数是1, 'requirement'  @11
- 执行storyModel模块的getRelation方法，参数是9, 'requirement'  @0
- 执行storyModel模块的getRelation方法，参数是11, 'story'  @1
- 执行storyModel模块的getRelation方法，参数是19, 'story'  @0

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";

$relation = zenData('relation');
$relation->AID->range('1,11,2,12,3,13,4,14,5,15,6,16,7,17,8,18');
$relation->BID->range('11,1,12,2,13,3,14,4,15,5,16,6,17,7,18,8');
$relation->gen(16);

global $tester;
$storyModel = $tester->loadModel('story');

r($storyModel->getRelation(0, 'requirement')) && p() && e('0');

r(key($storyModel->getRelation(1, 'requirement'))) && p() && e('11');
r($storyModel->getRelation(9, 'requirement')) && p() && e('0');

r(key($storyModel->getRelation(11, 'story'))) && p() && e('1');
r($storyModel->getRelation(19, 'story')) && p() && e('0');