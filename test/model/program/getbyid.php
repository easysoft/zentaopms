#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getById();
cid=1
pid=1

通过id字段获取id=1的项目集并验证它的name。 >> 项目集1
通过id字段获取id=1000的项目集，返回空 >> 0

*/

global $tester;
$tester->loadModel('program');
$program1 = $tester->program->getById(1);
$program2 = $tester->program->getById(1000);

r($program1) && p('name') && e('项目集1'); // 通过id字段获取id=1的项目集并验证它的name。
r($program2) && p()       && e('0');       // 通过id字段获取id=1000的项目集，返回空