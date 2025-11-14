#!/usr/bin/env php
<?php

/**

title=测试 storyModel->updateStoryReleasedDate();
timeout=0
cid=18599

- 判断更新后的需求1发布日期后是否正确。属性releasedDate @2022-02-02 00:00:00
- 判断更新后的需求2发布日期后是否正确。属性releasedDate @2022-02-02 00:00:00
- 判断更新后的需求3发布日期后是否正确。属性releasedDate @2022-02-02 00:00:00
- 判断更新后的需求4发布日期后是否正确。属性releasedDate @2022-02-02 00:00:00
- 判断更新后的需求5发布日期后是否正确。属性releasedDate @2022-02-02 00:00:00

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('story')->gen(10);

global $tester;
$tester->loadModel('story');

$tester->story->updateStoryReleasedDate('1,2,3,4,5', '2022-02-02');
$story1 = $tester->story->fetchByID(1);
$story2 = $tester->story->fetchByID(2);
$story3 = $tester->story->fetchByID(3);
$story4 = $tester->story->fetchByID(4);
$story5 = $tester->story->fetchByID(5);

r($story1) && p('releasedDate') && e('2022-02-02 00:00:00'); // 判断更新后的需求1发布日期后是否正确。
r($story2) && p('releasedDate') && e('2022-02-02 00:00:00'); // 判断更新后的需求2发布日期后是否正确。
r($story3) && p('releasedDate') && e('2022-02-02 00:00:00'); // 判断更新后的需求3发布日期后是否正确。
r($story4) && p('releasedDate') && e('2022-02-02 00:00:00'); // 判断更新后的需求4发布日期后是否正确。
r($story5) && p('releasedDate') && e('2022-02-02 00:00:00'); // 判断更新后的需求5发布日期后是否正确。