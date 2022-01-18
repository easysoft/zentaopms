#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('dev1');
/**

title=测试 programModel::getTopPairs();
cid=1
pid=1

*/

$program = $tester->loadModel('program');
a($program->getTopPairs());
r($program->getTopPairs()) && p() && e(''); // 
