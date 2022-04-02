#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getParentPM();
cid=1
pid=1

GetParentPM(). >> 0

*/

global $tester;
$tester->loadModel('program');

$programIdList = array(1, 2, 3);
$parentPM = $tester->program->getParentPM($programIdList);
a($parentPM);die;

/* GetParentPM($programIdList). */
r($getPM->getParentPM('1')) && p() && e('0'); //
