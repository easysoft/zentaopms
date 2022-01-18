#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('dev1');
/**

title=测试 programModel::getTopByID();
cid=1
pid=1

*/

$program = $tester->loadModel('program');
a($program->getTopByID(1));
r($program->getTopByID(1)) && p() && e(''); // 
