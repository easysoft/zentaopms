#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 testtaskModel->getToAndCcList();
cid=1
pid=1

*/

global $tester;
$testtask = $tester->loadModel('testtask');

$task1 = new stdclass();
$task2 = (object)array('owner' => '');
$task3 = (object)array('mailto' => '');
$task4 = (object)array('owner' => '', 'mailto' => '');
$task5 = (object)array('owner' => 'user1');
$task6 = (object)array('mailto' => 'user2');
$task7 = (object)array('mailto' => 'user2,user3');
$task8 = (object)array('owner' => 'user1', 'mailto' => 'user2');
$task9 = (object)array('owner' => 'user1', 'mailto' => 'user2,user3');

r($testtask->getToAndCcList($task1)) && p() && e(0);
r($testtask->getToAndCcList($task2)) && p() && e(0);
r($testtask->getToAndCcList($task3)) && p() && e(0);
r($testtask->getToAndCcList($task4)) && p() && e(0);
r($testtask->getToAndCcList($task5)) && p('0,1') && e('user1,~~');
r($testtask->getToAndCcList($task6)) && p('0,1') && e('user2,~~');
r($testtask->getToAndCcList($task7)) && p('0,1') && e('user2,user3');
r($testtask->getToAndCcList($task8)) && p('0,1') && e('user1,user2');
r($testtask->getToAndCcList($task9)) && p('0|1', '|') && e('user1|user2,user3');
