#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::setTreePath();
cid=1
pid=1

设置之前的项目集的path >> ,2,12,
设置之后的项目集的path >> ,2,12,

*/

global $tester;
$tester->loadModel('program');

$before = $tester->program->getById(12);
$tester->program->setTreePath(12);
$after  = $tester->program->getById(12);

r($before) && p('path') && e(',2,12,'); // 设置之前的项目集的path
r($after)  && p('path') && e(',2,12,'); // 设置之后的项目集的path
$db->restoreDB();