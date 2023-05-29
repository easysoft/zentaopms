#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';

zdTable('story')->gen(5);
zdTable('bug')->gen(1);
$file = zdTable('file');
$file->objectType->range('bug');
$file->objectID->range('1');
$file->gen(5);

/**

title=测试 storyModel->closeBugWhenToStory();
cid=1
pid=1

*/

$storyTest = new storyTest();

r($storyTest->closeBugWhenToStoryTest(0, 0)) && p() && e('0'); //不传入Bug，也不传入需求。
r($storyTest->closeBugWhenToStoryTest(1, 0)) && p() && e('0'); //传入Bug，不传入需求。
r($storyTest->closeBugWhenToStoryTest(0, 1)) && p() && e('0'); //不传入Bug，传入需求。

$bug = $storyTest->closeBugWhenToStoryTest(1, 1);
r($bug)                 && p('toStory,status,resolution') && e('1,closed,tostory'); //传入Bug，传入需求，检查字段。
r(count($bug['files'])) && p()                            && e('5');                //传入Bug，传入需求，检查关联的附件。
