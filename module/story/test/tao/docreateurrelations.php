#!/usr/bin/env php
<?php

/**

title=测试 storyModel->doCreateURRelations();
cid=0

- 不传入任何数据。 @0
- 只传入用户需求列表。 @0
- 只传入软件需求 ID。 @0
- 传入软件需求 ID 和 用户需求列表，查看relation表记录的数量。 @2
- 传入软件需求 ID 和 用户需求列表，查看relation表记录的关系。
 - 属性AID @1
 - 属性BID @2
 - 属性relation @subdivideinto
- 传入软件需求 ID 和 用户需求列表，查看relation表记录的关系。
 - 属性AID @2
 - 属性BID @1
 - 属性relation @subdividedfrom
- 传入的用户需求列表，不是用户需求类型。 @0
- 传入的用户需求列表，需求不存在。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('product')->gen(2);
zdTable('relation')->gen(0);

$story = zdTable('story');
$story->type->range('requirement,story{10}');
$story->parent->range('0,0,0,0');
$story->product->range('1');
$story->version->range('1');
$story->gen(4);

$storySpec = zdTable('storyspec');
$storySpec->story->range('1-6');
$storySpec->gen(4);

global $tester;
$storyModel = $tester->loadModel('story');

r($storyModel->doCreateURRelations(0, array()))  && p() && e('0'); // 不传入任何数据。
r($storyModel->doCreateURRelations(0, array(1))) && p() && e('0'); // 只传入用户需求列表。
r($storyModel->doCreateURRelations(2, array()))  && p() && e('0'); // 只传入软件需求 ID。

$story = new storyTest();
$requirementResult = $story->doCreateURRelationsTest(2, array(1));

r(count($requirementResult)) && p()                   && e('2');                  // 传入软件需求 ID 和 用户需求列表，查看relation表记录的数量。
r($requirementResult[0])     && p('AID,BID,relation') && e('1,2,subdivideinto');  // 传入软件需求 ID 和 用户需求列表，查看relation表记录的关系。
r($requirementResult[1])     && p('AID,BID,relation') && e('2,1,subdividedfrom'); // 传入软件需求 ID 和 用户需求列表，查看relation表记录的关系。

r($story->doCreateURRelationsTest(3, array(4)))  && p() && e('0'); // 传入的用户需求列表，不是用户需求类型。
r($story->doCreateURRelationsTest(3, array(10))) && p() && e('0'); // 传入的用户需求列表，需求不存在。
