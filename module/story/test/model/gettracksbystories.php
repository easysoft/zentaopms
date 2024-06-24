#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getTracksByStories();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('story')->gen(10);

su('admin');

global $tester;
$tester->loadModel('story');

$tester->story->lang->ERCommon = '业务需求';
$tester->story->lang->URCommon = '用户需求';
$tester->story->lang->SRCommon = '研发需求';

$tracks = $tester->story->getTracksByStories(array(), 'epic');
r(empty($tracks)) && p() && e('1');  //传入空参数。

$stories = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in(array('2,3,4,5'))->fetchAll('id');
$tracks = $tester->story->getTracksByStories($stories, 'epic');
r(isset($tracks['lanes']) && isset($tracks['cols']) && isset($tracks['items'])) && p() && e('1'); //检查返回数据的 lanes，cols，items。
