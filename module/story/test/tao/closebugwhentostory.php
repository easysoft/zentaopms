#!/usr/bin/env php
<?php

/**

title=测试 storyModel->closeBugWhenToStory();
cid=18612

- 不传入Bug，也不传入需求。 @0
- 传入Bug，不传入需求。 @0
- 不传入Bug，传入需求。 @0
- 传入Bug，传入需求，检查字段。
 - 属性toStory @1
 - 属性status @closed
 - 属性resolution @tostory
- 传入Bug，传入需求，检查关联的附件。 @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('story')->gen(5);
zenData('bug')->gen(1);
$file = zenData('file');
$file->objectType->range('bug');
$file->objectID->range('1');
$file->gen(5);

$storyTest = new storyTest();

r($storyTest->closeBugWhenToStoryTest(0, 0)) && p() && e('0'); //不传入Bug，也不传入需求。
r($storyTest->closeBugWhenToStoryTest(1, 0)) && p() && e('0'); //传入Bug，不传入需求。
r($storyTest->closeBugWhenToStoryTest(0, 1)) && p() && e('0'); //不传入Bug，传入需求。

$bug = $storyTest->closeBugWhenToStoryTest(1, 1);
r($bug)                 && p('toStory,status,resolution') && e('1,closed,tostory'); //传入Bug，传入需求，检查字段。
r(count($bug['files'])) && p()                            && e('5');                //传入Bug，传入需求，检查关联的附件。
