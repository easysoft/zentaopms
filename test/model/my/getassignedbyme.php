#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/my.class.php';
su('admin');

/**

title=测试 myModel->getAssignedByMe();
cid=1
pid=1

当插入一条action为admin指派了task的动态时，查询结果为1条数据 >> 1
当插入一条action为admin指派了bug的动态时，查询结果为1条数据 >> 1
当插入一条action为admin指派了需求的动态时，查询结果为0条数据 >> 0

*/

$my    = new myTest();
$task  = array('admin', 0, '', 'id_desc', 0, 'task');
$bug   = array('admin', 0, '', 'id_desc', 0, 'bug');
$story = array('admin', 0, '', 'id_desc', 0, 'story');

r($my->getAssignedByMeTest($task[0],  $task[1],  $task[2],  $task[3],  $task[4],  $task[5]))  && p() && e('1'); // 当插入一条action为admin指派了task的动态时，查询结果为1条数据
r($my->getAssignedByMeTest($bug[0],   $bug[1],   $bug[2],   $bug[3],   $bug[4],   $bug[5]))   && p() && e('1'); // 当插入一条action为admin指派了bug的动态时，查询结果为1条数据
r($my->getAssignedByMeTest($story[0], $story[1], $story[2], $story[3], $story[4], $story[5])) && p() && e('0'); // 当插入一条action为admin指派了需求的动态时，查询结果为0条数据

