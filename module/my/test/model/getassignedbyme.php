#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('action')->loadYaml('action_assigned')->gen('30', true, false);
zenData('bug')->gen('10');
zenData('task')->gen('10');
zenData('story')->gen('10');
zenData('user')->gen('1');
zenData('project')->gen('0');
zenData('product')->gen('10');

su('admin');

/**

title=测试 myModel->getAssignedByMe();
timeout=0
cid=1

- 当插入一条action为admin指派了task的动态时，查询结果为1条数据 @0
- 当插入一条action为admin指派了bug的动态时，查询结果为1条数据 @4
- 当插入一条action为admin指派了需求的动态时，查询结果为0条数据 @0

*/

$my    = new myTest();
$task  = array('admin', 'id_desc', 'task');
$bug   = array('admin', 'id_desc', 'bug');
$story = array('admin', 'id_desc', 'story');

r($my->getAssignedByMeTest($task[0],  $task[1],  $task[2]))  && p() && e('0'); // 当插入一条action为admin指派了task的动态时，查询结果为1条数据
r($my->getAssignedByMeTest($bug[0],   $bug[1],   $bug[2]))   && p() && e('4'); // 当插入一条action为admin指派了bug的动态时，查询结果为1条数据
r($my->getAssignedByMeTest($story[0], $story[1], $story[2])) && p() && e('0'); // 当插入一条action为admin指派了需求的动态时，查询结果为0条数据