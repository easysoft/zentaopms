#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getTeamMemberPairs();
cid=1
pid=1

*/

$program = $tester->loadModel('program');
r($program->getTeamMemberPairs()) && p() && e(''); // 
