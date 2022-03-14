#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::hasUnfinished();
cid=1
pid=1

获取项目集1下未完成的项目和项目集 >> 88

*/

$program = new Program('admin');

$unFinish = 1;

r($program->getUnfinished($unFinish)) && p() && e('88'); // 获取项目集1下未完成的项目和项目集