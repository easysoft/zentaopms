#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

su('admin');

/**

title=测试 programModel::getKanbanGroup();
cid=1
pid=1

*/

$program = $tester->loadModel('program');

$result = 'Not Found';
if(empty($program->getKanbanGroup()['my'])) r($result) && p() && e($result); // 获取我的看板组
if(empty($program->getKanbanGroup()['other'])) r($result) && p() && e($result); // 获取其他看板组
