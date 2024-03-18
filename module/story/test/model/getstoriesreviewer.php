#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getstoriesreviewer();
cid=0

- 查看激活之后的需求状态 @A:admin|U:用户1|U:用户2|U:用户3|U:用户4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->gen(4);
zdTable('user')->gen(5);

global $tester;
$tester->loadModel('story');

$reviewers = $tester->story->getStoriesReviewer(1);

r(implode('|', $reviewers))  && p() && e('A:admin|U:用户1|U:用户2|U:用户3|U:用户4');  //查看激活之后的需求状态
