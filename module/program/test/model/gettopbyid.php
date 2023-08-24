#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=测试 programModel::getTopByID();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('program');
$top1    = $tester->program->getTopByID(1);
$top1000 = $tester->program->getTopByID(1000);

r($top1)    && p() && e('1'); //获取项目集1最上级的项目集id
r($top1000) && p() && e('0'); //获取项目集1000最上级的项目集id
