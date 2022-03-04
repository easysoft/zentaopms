#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=executionModel->getByID();
cid=1
pid=1

根据executionID查找任务详情 >> 迭代1

*/

$executionID = '101';

$execution = new executionTest();
r($execution->getByIDTest($executionID)) && p('name') && e('迭代1'); //根据executionID查找任务详情