#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

/**

title=测试 personnelModel->getInvolvedExecutions();
cid=1
pid=1

*/

$personnel = new personnelTest('admin');

$project = array();
$project[0] = 101;
$project[1] = 102;

r($personnel->getInvolvedExecutionsTest($project)) && p() && e(''); //
