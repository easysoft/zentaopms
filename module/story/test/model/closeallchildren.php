#!/usr/bin/env php
<?php
/**

title=测试 storyModel->closeAllChildren();
timeout=0
cid=18484

- 关闭父需求ID为1所有子需求关闭原因为已完成属性closedReason @done
- 关闭父需求ID为4所有子需求关闭原因为已拆分属性closedReason @subdivided
- 关闭父需求ID为7所有子需求关闭原因为已复制属性closedReason @duplicate
- 关闭父需求ID为10所有子需求关闭原因为延期属性closedReason @postponed
- 关闭父需求ID为13所有子需求关闭原因为不做属性closedReason @willnotdo
- 关闭父需求ID为16所有子需求关闭原因为已取消属性closedReason @cancel
- 关闭父需求ID为19所有子需求关闭原因为设计如此属性closedReason @bydesign

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

$storyTable = zenData('story');
$storyTable->version->range('1');
$storyTable->parent->range('0,1{2},0,4{2},0,7{2},0,10{2},0,13{2},0,16{2},0,19{2}');
$storyTable->gen(21)->fixPath();
zenData('storyspec')->gen(21);

$storyIdList = array(1, 4, 7, 10, 13, 16, 19);
$reasonList  = array('done', 'subdivided', 'duplicate', 'postponed', 'willnotdo', 'cancel', 'bydesign');

$storyTester = new storyTest();
r($storyTester->closeAllChildrenTest($storyIdList[0], $reasonList[0])) && p('closedReason') && e('done');       // 关闭父需求ID为1所有子需求关闭原因为已完成
r($storyTester->closeAllChildrenTest($storyIdList[1], $reasonList[1])) && p('closedReason') && e('subdivided'); // 关闭父需求ID为4所有子需求关闭原因为已拆分
r($storyTester->closeAllChildrenTest($storyIdList[2], $reasonList[2])) && p('closedReason') && e('duplicate');  // 关闭父需求ID为7所有子需求关闭原因为已复制
r($storyTester->closeAllChildrenTest($storyIdList[3], $reasonList[3])) && p('closedReason') && e('postponed');  // 关闭父需求ID为10所有子需求关闭原因为延期
r($storyTester->closeAllChildrenTest($storyIdList[4], $reasonList[4])) && p('closedReason') && e('willnotdo');  // 关闭父需求ID为13所有子需求关闭原因为不做
r($storyTester->closeAllChildrenTest($storyIdList[5], $reasonList[5])) && p('closedReason') && e('cancel');     // 关闭父需求ID为16所有子需求关闭原因为已取消
r($storyTester->closeAllChildrenTest($storyIdList[6], $reasonList[6])) && p('closedReason') && e('bydesign');   // 关闭父需求ID为19所有子需求关闭原因为设计如此
