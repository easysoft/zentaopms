#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getProjectList($programID = 0, $browseType = 'all', $queryID = 0, $orderBy = 'id_desc', $pager = null, $programTitle = 0, $involved = 0, $queryAll = false);
cid=1
pid=1

*/

$program = $tester->loadModel('program');
r($program->getProjectList()) && p() && e(''); // 通过id字段获取存在的项目集
