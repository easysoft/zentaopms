#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

su('dev1');

/**

title=测试 programModel::getTopByID();
cid=1
pid=1

获取项目集1最上级的项目集id >> Not Found

*/

$t = new Program('admin');

$program_id = 1;

r($t->getByID3($program_id)) && p('message') && e('Not Found'); // 获取项目集1最上级的项目集id