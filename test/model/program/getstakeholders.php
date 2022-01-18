#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getStakeholders($programID = 0, $orderBy = 'id_desc', $pager = null);
cid=1
pid=1

*/

$program = $tester->loadModel('program');
r($program->getStakeholders()) && p() && e(''); // 通过id字段获取存在的项目集
