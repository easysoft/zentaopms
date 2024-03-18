#!/usr/bin/env php
<?php

/**

title=测试 storyModel->finishTodoWhenToStory();
cid=0

- 不传入待办，也不传入需求。 @0
- 传入待办，不传入需求。 @0
- 不传入待办，传入需求。 @0
- 传入待办，也传入需求。 @done
- 传入关联反馈的待办，传入需求。 @done

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';

zdTable('story')->gen(5);
$feedback = zdTable('feedback')->gen(1);
$todo = zdTable('todo');
$todo->type->range('custom,feedback');
$todo->objectID->range('0,1');
$todo->gen(2);

$storyTest = new storyTest();

r($storyTest->finishTodoWhenToStoryTest(0, 0)) && p() && e('0');    //不传入待办，也不传入需求。
r($storyTest->finishTodoWhenToStoryTest(1, 0)) && p() && e('0');    //传入待办，不传入需求。
r($storyTest->finishTodoWhenToStoryTest(0, 1)) && p() && e('0');    //不传入待办，传入需求。
r($storyTest->finishTodoWhenToStoryTest(1, 1)) && p() && e('done'); //传入待办，也传入需求。
r($storyTest->finishTodoWhenToStoryTest(2, 2)) && p() && e('done'); //传入关联反馈的待办，传入需求。
