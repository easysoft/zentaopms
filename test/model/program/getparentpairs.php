#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

su('admin');

/**

title=测试 programModel::getParentPairs($model = '', $mode = 'noclosed');
cid=1
pid=1

*/

$program = $tester->loadModel('program');
r($program->getParentPairs()) && p('1') && e(''); // 获取父项目集
