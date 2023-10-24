#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->subdivide();
cid=1
pid=1

将用户需求1拆分三个软件需求，查看relation表记录的数量 >> 3
将用户需求1拆分三个软件需求，查看relation表记录的关系 >> 1,2,subdivideinto
将用户需求1拆分三个软件需求，查看relation表记录的关系 >> 1,6,subdivideinto
将软件需求2拆分三个子需求，查看子需求的数量 >> 3
将软件需求2拆分三个子需求，查看子需求8的parent、title等字段 >> 2,这里是需求来源备注8,8
将软件需求2拆分三个子需求，查看子需求12的parent、title等字段 >> 2,这里是需求来源备注12,12

*/

$story = new storyTest();
$storyIdList1 = array(2, 4, 6);
$storyIdList2 = array(8, 10, 12);

$result1 = $story->subdivideTest(1, $storyIdList1, 'requirement');
$result2 = $story->subdivideTest(2, $storyIdList2, 'story');

r(count($result1))           && p()                     && e('3');                         // 将用户需求1拆分三个软件需求，查看relation表记录的数量
r($result1)                  && p('0:AID,BID,relation') && e('1,2,subdivideinto');         // 将用户需求1拆分三个软件需求，查看relation表记录的关系
r($result1)                  && p('2:AID,BID,relation') && e('1,6,subdivideinto');         // 将用户需求1拆分三个软件需求，查看relation表记录的关系
r(count($result2->children)) && p()                     && e('3');                         // 将软件需求2拆分三个子需求，查看子需求的数量
r($result2->children)        && p('8:parent,title,id')  && e('2,这里是需求来源备注8,8');   // 将软件需求2拆分三个子需求，查看子需求8的parent、title等字段
r($result2->children)        && p('12:parent,title,id') && e('2,这里是需求来源备注12,12'); // 将软件需求2拆分三个子需求，查看子需求12的parent、title等字段
