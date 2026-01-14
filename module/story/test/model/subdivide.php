#!/usr/bin/env php
<?php

/**

title=测试 storyModel->subdivide();
timeout=0
cid=18587

- 将用户需求1拆分两个软件需求，查看relation表记录的关系。
 - 属性isParent @1
 - 属性root @0
 - 属性grade @1
- 将软件需求2拆分两个子需求，查看子需求的数量。 @2
- 将软件需求2拆分两个子需求，查看父需求的parent字段。 @0
- 将软件需求2拆分两个子需求，查看子需求5的parent字段。 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('product')->gen(2);
zenData('relation')->gen(1);

$story = zenData('story');
$story->type->range('requirement,story{10}');
$story->parent->range('0,0,0,0,4,4');
$story->product->range('1');
$story->version->range('1');
$story->gen(6);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-6');
$storySpec->gen(6);

$story = new storyModelTest();
$requirementResult = $story->subdivideTest(1, array(2, 3), 'requirement');
$childrenResult    = $story->subdivideTest(4, array(5, 6), 'story');

r($requirementResult)                   && p('isParent,root,grade') && e('1,0,1'); // 将用户需求1拆分两个软件需求，查看relation表记录的关系。
r(count($childrenResult->children))     && p()                      && e('2');     // 将软件需求2拆分两个子需求，查看子需求的数量。
r($childrenResult->parent)              && p()                      && e('0');     // 将软件需求2拆分两个子需求，查看父需求的parent字段。
r($childrenResult->children[0]->parent) && p()                      && e('4');     // 将软件需求2拆分两个子需求，查看子需求5的parent字段。