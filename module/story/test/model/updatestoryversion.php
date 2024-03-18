#!/usr/bin/env php
<?php

/**

title=测试 storyModel->updateStoryVersion();
cid=0

- 执行story模块的updateStoryVersionTest方法，参数是12 属性AVersion @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('story')->gen(20);
zdTable('product')->gen(1);
$story = zdTable('story');
$story->product->range(1);
$story->version->range(1);
$story->gen(20);

$storySpec = zdTable('storyspec');
$storySpec->story->range('1-20{2}');
$storySpec->version->range('1-2');
$storySpec->gen(40);
zdTable('storyspec')->gen(20);
$relation = zdTable('relation');
$relation->AID->range('1,10,2,11,3,12,4,13,5,14,6,15,7,16,8,17');
$relation->AVersion->range('1');
$relation->BID->range('10,1,11,2,12,3,13,4,14,5,15,6,16,7,17,8');
$relation->BVersion->range('1');
$relation->gen(16);

$story = new storyTest();
r($story->updateStoryVersionTest(12)) && p('AVersion') && e('2');
