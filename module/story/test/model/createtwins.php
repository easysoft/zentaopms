#!/usr/bin/env php
<?php
/**

title=测试 storyModel->create();
cid=1

- 检查创建后的数据。
 - 属性id @5
 - 属性twins @,6,

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('product')->gen(2);
zdTable('project')->gen(2);
zdTable('bug')->gen(2);
zdTable('relation')->gen(0);
zdTable('storyreview')->gen(0);
zdTable('projectstory')->gen(0);

$story = zdTable('story');
$story->type->range('requirement,story{10}');
$story->parent->range('0,0,0,0');
$story->product->range('1');
$story->version->range('1');
$story->gen(4);

$storySpec = zdTable('storyspec');
$storySpec->story->range('1-6');
$storySpec->gen(4);

$data  = new stdclass();
$data->product     = 1;
$data->module      = 0;
$data->branches    = array(0, 1);
$data->modules     = array(1, 2);
$data->plans       = array(0, 0);
$data->plan        = 1;
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
$data->mailto      = '';
$data->parent      = 1;
$data->URS[]       = 1;
$data->reviewer[]  = 'admin';

$story = new storyTest();
$test1 = $story->createTwinsTest($data);
r((array)$test1) && p('id-twins', '-') && e('5-,6,'); //检查创建后的数据。
