#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$story = zdTable('story');
$story->id->range('1-100');
$story->title->range('teststory');
$story->product->range('1');
$story->parent->range('`-1`,`-1`,0,1,1');
$story->version->range('1');
$story->gen(5);

/**

title=测试 storyModel->doUpdateSpec();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');

$story = new stdclass();
$story->product = 1;
$story->parent  = 0;

r((array)$storyModel->doUpdateParentStatus(1, 'active')) && p('status,closedBy') && e('active,~~');
r((array)$storyModel->doUpdateParentStatus(2, 'closed')) && p('status,closedBy') && e('closed,admin');
