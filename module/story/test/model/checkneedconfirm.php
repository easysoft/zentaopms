#!/usr/bin/env php
<?php

/**

title=测试 storyModel->checkNeedConfirm();
cid=0

- 执行$tester->story->checkNeedConfirm($data)->needconfirm @1
- 执行$tester->story->checkNeedConfirm(array(1 => $data))[1]->needconfirm @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('story')->gen(10);

$data = new stdclass();
$data->id    = 1;
$data->story = 2;
$data->storyVersion = 1;

global $tester;
$tester->loadModel('story');

r((int)$tester->story->checkNeedConfirm($data)->needconfirm) && p() && e('1');
r((int)$tester->story->checkNeedConfirm(array(1 => $data))[1]->needconfirm) && p() && e('1');
