#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';

zdTable('story')->gen(10);

/**

title=测试 storyModel->updateStoryReleasedDate();
cid=1
pid=1

*/

global $tester;

$tester->loadModel('story')->updateStoryReleasedDate('1,2,3', '2022-02-02');
$story1 = $tester->story->fetchByID(1);
$story2 = $tester->story->fetchByID(2);
$story3 = $tester->story->fetchByID(3);

r($story1) && p('releasedDate') && e('2022-02-02 00:00:00'); // 判断更新需求1发布日期后是否正确。
r($story2) && p('releasedDate') && e('2022-02-02 00:00:00'); // 判断更新需求2发布日期后是否正确。
r($story3) && p('releasedDate') && e('2022-02-02 00:00:00'); // 判断更新需求3发布日期后是否正确。
