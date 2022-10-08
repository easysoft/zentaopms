#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/design.class.php';
su('admin');

/**

title=测试 designModel->assign();
cid=1
pid=1

设置指派人 >> dev10

*/
$designID = '17';

$normalAssign = array('assignedTo' => 'dev10', 'comment' => '提交信息');
$noAssignedTo = array('comment' => '提交信息');

$design = new designTest();
r($design->assignTest($designID, $normalAssign)) && p('0:assignedTo') && e('dev10');//设置指派人
r($design->assignTest($designID, $noAssignedTo)) && p('0:assignedTo') && e('');     //指派人为空