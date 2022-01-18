#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::setTreePath();
cid=1
pid=1

*/

$program = $tester->loadModel('program');
r($program->setTreePath(11)) && p() && e('1'); // 设置id=11的项目集的path
r($program->getById(11)) && p('path') && e('11,'); // 查找id=11的项目集的path
