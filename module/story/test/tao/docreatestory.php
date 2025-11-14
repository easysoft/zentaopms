#!/usr/bin/env php
<?php

/**

title=测试 storyModel->doCreateStory();
timeout=0
cid=18619

- 检查保存后的数据。
 - 属性product @1
 - 属性title @test story
 - 属性status @active
 - 属性pri @3
- 检查报错信息。 @『通知邮箱』应当为合法的EMAIL。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

zenData('story')->gen(0);

$data  = new stdclass();
$data->product     = 1;
$data->module      = 0;
$data->modules     = array(0);
$data->plans       = array(0);
$data->plan        = 0;
$data->assignedTo  = '';
$data->source      = '';
$data->sourceNote  = '';
$data->feedbackBy  = '';
$data->notifyEmail = '';
$data->parent      = 0;
$data->title       = 'test story';
$data->color       = '';
$data->category    = 'feature';
$data->pri         = 3;
$data->estimate    = 1;
$data->spec        = 'test spec';
$data->verify      = 'test verify';
$data->keywords    = '';
$data->type        = 'story';
$data->status      = 'active';
$data->version     = 1;
$data->openedBy    = 'admin';
$data->openedDate  = date('Y-m-d H:i:s');

$storyTest = new storyTest();
$story = $storyTest->doCreateStoryTest($data);
r($story) && p('product,title,status,pri') && e('1,test story,active,3'); //检查保存后的数据。

$data->notifyEmail = 'test';
$error = $storyTest->doCreateStoryTest($data);
r($error['notifyEmail'][0]) && p() && e('『通知邮箱』应当为合法的EMAIL。'); //检查报错信息。