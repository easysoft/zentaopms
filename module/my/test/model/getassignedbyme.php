#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('action')->config('action_assigned')->gen('30', true, false);
zdTable('bug')->gen('10');
zdTable('task')->gen('10');
zdTable('story')->gen('10');
zdTable('user')->gen('1');
zdTable('project')->gen('0');

su('admin');

/**

title=测试 myModel->getAssignedByMe();
cid=1
pid=1

*/

$my    = new myTest();
$task  = array('admin', 'id_desc', 'task');
$bug   = array('admin', 'id_desc', 'bug');
$story = array('admin', 'id_desc', 'story');

r($my->getAssignedByMeTest($task[0],  $task[1],  $task[2]))  && p() && e('0'); // 当插入一条action为admin指派了task的动态时，查询结果为1条数据
r($my->getAssignedByMeTest($bug[0],   $bug[1],   $bug[2]))   && p() && e('4'); // 当插入一条action为admin指派了bug的动态时，查询结果为1条数据
r($my->getAssignedByMeTest($story[0], $story[1], $story[2])) && p() && e('0'); // 当插入一条action为admin指派了需求的动态时，查询结果为0条数据
