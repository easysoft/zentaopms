#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getBySearch();
cid=1
pid=1

根据第二个query获取需求数量 >> 1
根据第二个query获取需求 >> 软件需求362,story,55,91

*/

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getBySearch(91, '', 2);

r(count($stories1)) && p()                              && e('1');                        // 根据第二个query获取需求数量
r($stories1)        && p('362:title,type,plan,product') && e('软件需求362,story,55,91');  // 根据第二个query获取需求