#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('serverroom')->config('serverroom')->gen(10);
zdTable('userquery')->config('roomquery')->gen(1);
su('admin');

/**

title=serverroomModel->getList();
timeout=0
cid=1

*/

global $tester;
$roomModel = $tester->loadModel('serverroom');

/* all. */
$allRooms = $roomModel->getList('all', 0, 'id_asc');
r(count($allRooms)) && p() && e('9'); // 检查所有数据的数量。
r($allRooms) && p('1:name,city,line;10:name,city,line') && e('机房1,beijing,telecom,机房10,beijing,bgp'); // 检查第一条和最后一条数据。

/* search */
$searchRooms = $roomModel->getList('bysearch', 1, 'id_asc');
r(count($searchRooms)) && p() && e('2'); // 检查搜索数据的数量。
r($searchRooms) && p('1:name,city,line') && e('机房1,beijing,telecom'); // 检查第一条数据。
