#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getTracksByStories();
timeout=0
cid=0

- 传入空参数。 @1
- 查看泳道数量 @1
- 查看列数量 @16
- 查看卡片数量 @1
- 查看列详情
 - 第0条的name属性 @epic
 - 第0条的title属性 @业务需求

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('story')->gen(10);

su('admin');

global $tester;
$tester->loadModel('story');

$tester->story->lang->ERCommon = '业务需求';
$tester->story->lang->URCommon = '用户需求';
$tester->story->lang->SRCommon = '研发需求';

$tracks = $tester->story->getTracksByStories(array(), 'epic', 'allstory', 'id_desc');
r(empty($tracks)) && p() && e('1');  //传入空参数。

$stories = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in(array('2,3,4,5'))->fetchAll('id');
$tracks = $tester->story->getTracksByStories($stories, 'epic', 'allstory', 'id_desc');
r(count($tracks['lanes'])) && p() && e('1'); // 查看泳道数量
r(count($tracks['cols'])) && p() && e('16'); // 查看列数量
r(count($tracks['items'])) && p() && e('1'); // 查看卡片数量

r($tracks['cols']) && p('0:name,title') && e('epic,业务需求'); // 查看列详情