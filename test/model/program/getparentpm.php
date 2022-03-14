#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getParentPM();
cid=1
pid=1

GetParentPM(). >> 0

*/

$getPM = new Program('admin');

/* GetParentPM($programIdList). */
r($getPM->getParentPM('1')) && p() && e('0'); //