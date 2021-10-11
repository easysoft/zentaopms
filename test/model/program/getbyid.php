#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getById();
cid=1
pid=1

通过id字段获取存在的项目集 >> 测试项目集1

*/

$program = $tester->loadModel('program');
r($program->getById(1)) && p('name') && e('测试项目集1'); // 通过id字段获取存在的项目集