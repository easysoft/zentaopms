#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->batchChangeStage();
cid=1
pid=1

批量修改6个需求的阶段，查看被修改阶段的需求数量 >> 5
批量修改6个需求的阶段，查看需求101修改后的阶段 >> developing
批量修改6个需求的阶段，查看需求102修改后的阶段 >> developing
批量修改6个需求的阶段，查看需求104修改后的阶段 >> developing
批量修改6个需求的阶段，查看需求110修改后的阶段 >> developing
批量修改6个需求的阶段，查看需求114修改后的阶段 >> developing

*/

$story       = new storyTest();
$storyIdList = array(100, 101, 102, 106, 110, 114);
$result      = $story->batchChangeStageTest($storyIdList, 'developing');

r(count($result))  && p()            && e('5');          // 批量修改6个需求的阶段，查看被修改阶段的需求数量
r($result)         && p('101:stage') && e('developing'); // 批量修改6个需求的阶段，查看需求101修改后的阶段
r($result)         && p('102:stage') && e('developing'); // 批量修改6个需求的阶段，查看需求102修改后的阶段
r($result)         && p('106:stage') && e('developing'); // 批量修改6个需求的阶段，查看需求104修改后的阶段
r($result)         && p('110:stage') && e('developing'); // 批量修改6个需求的阶段，查看需求110修改后的阶段
r($result)         && p('114:stage') && e('developing'); // 批量修改6个需求的阶段，查看需求114修改后的阶段
