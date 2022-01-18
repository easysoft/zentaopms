#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

su('admin');

/**

title=测试 programModel::getParentPM($programIdList);
cid=1
pid=1

*/

$program = $tester->loadModel('program');
r($program->getParentPM()) && p() && e(''); // 
