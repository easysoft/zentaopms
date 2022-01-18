#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::hasUnfinished();
cid=1
pid=1

*/

$program     = $tester->loadModel('program');
$programInfo = $program->getById(1); 
r($program->hasUnfinished($programInfo)) && p() && e(''); // 通过id字段获取存在的项目集
