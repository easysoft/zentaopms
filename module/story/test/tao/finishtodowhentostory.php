#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';

zdTable('story')->gen(5);
$feedback = zdTable('feedback')->gen(1);
$todo = zdTable('todo');
$todo->type->range('custom,feedback');
$todo->objectID->range('0,1');
$todo->gen(2);

/**

title=测试 storyModel->finishTodoWhenToStory();
cid=1
pid=1

*/

$storyTest = new storyTest();

r($storyTest->finishTodoWhenToStoryTest(0, 0)) && p() && e('0'); //不传入需求，也不传入产品。
r($storyTest->finishTodoWhenToStoryTest(1, 0)) && p() && e('0'); //不传入需求，也不传入产品。
r($storyTest->finishTodoWhenToStoryTest(0, 1)) && p() && e('0'); //不传入需求，也不传入产品。
r($storyTest->finishTodoWhenToStoryTest(1, 1)) && p() && e('done'); //不传入需求，也不传入产品。
r($storyTest->finishTodoWhenToStoryTest(2, 2)) && p() && e('done'); //不传入需求，也不传入产品。
