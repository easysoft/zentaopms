#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('product')->gen(2);
zdTable('relation')->gen(1);

$story = zdTable('story');
$story->type->range('requirement,story{10}');
$story->parent->range('0,0,0,0,4,4');
$story->product->range('1');
$story->version->range('1');
$story->gen(6);

$storySpec = zdTable('storyspec');
$storySpec->story->range('1-6');
$storySpec->gen(6);

/**

title=测试 storyModel->subdivide();
cid=1
pid=1

*/

$story = new storyTest();
$requirementResult = $story->subdivideTest(1, array(2, 3), 'requirement');
$childrenResult    = $story->subdivideTest(4, array(5, 6), 'story');

r(count($requirementResult))            && p()                   && e('4');                  // 将用户需求1拆分两个软件需求，查看relation表记录的数量。
r($requirementResult[0])                && p('AID,BID,relation') && e('1,2,subdivideinto');  // 将用户需求1拆分两个软件需求，查看relation表记录的关系。
r($requirementResult[1])                && p('AID,BID,relation') && e('2,1,subdividedfrom'); // 将用户需求1拆分两个软件需求，查看relation表记录的关系。
r($requirementResult[2])                && p('AID,BID,relation') && e('1,3,subdivideinto');  // 将用户需求1拆分两个软件需求，查看relation表记录的关系。
r($requirementResult[3])                && p('AID,BID,relation') && e('3,1,subdividedfrom'); // 将用户需求1拆分两个软件需求，查看relation表记录的关系。
r(count($childrenResult->children))     && p()                   && e('2');                  // 将软件需求2拆分两个子需求，查看子需求的数量。
r($childrenResult->parent)              && p()                   && e('-1');                 // 将软件需求2拆分两个子需求，查看父需求的parent字段。
r($childrenResult->children[5]->parent) && p()                   && e('4');                  // 将软件需求2拆分两个子需求，查看子需求5的parent字段。
