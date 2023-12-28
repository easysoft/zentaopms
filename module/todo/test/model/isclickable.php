#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

/**

title=测试 todoModel->isClickable();
timeout=0
cid=1

- 测试cyclei的值不为空并且action为finish的情况下 @2

- 测试cyclei的值不为空并且action为start的情况下 @2

- 测试cyclei的值为空并且action为finish的情况下 @2

- 测试cyclei的值为空并且action为start的情况下 @1

- 测试cyclei的值为空并且action为done的情况下 @1

*/

function initData()
{
    zdTable('todo')->config('isclickable')->gen(3);
}

initData();

$todo = new todoTest();

$action = array('finish', 'start', 'done');

$todoData1 = new stdclass();
$todoData1->cycle   = 'click';
$todoData1->status  = 'wait';
$todoData1->private = 0;

$todoData2 = new stdclass();
$todoData2->cycle   = '';
$todoData2->status  = 'done';
$todoData2->private = 0;

$todoData3 = new stdclass();
$todoData3->cycle   = '';
$todoData3->status  = 'wait';
$todoData3->private = 0;

r($todo->isClickableTest($todoData1, $action[0])) && p() && e('2'); // 测试cyclei的值不为空并且action为finish的情况下
r($todo->isClickableTest($todoData1, $action[1])) && p() && e('2'); // 测试cyclei的值不为空并且action为start的情况下
r($todo->isClickableTest($todoData2, $action[0])) && p() && e('2'); // 测试cyclei的值为空并且action为finish的情况下
r($todo->isClickableTest($todoData3, $action[1])) && p() && e('1'); // 测试cyclei的值为空并且action为start的情况下
r($todo->isClickableTest($todoData3, $action[2])) && p() && e('1'); // 测试cyclei的值为空并且action为done的情况下
