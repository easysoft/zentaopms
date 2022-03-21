#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::processNode();
cid=1
pid=1

 >> 1

*/

$program = new Program('admin');

r($program->processNode(1, 0, 1, 1)) && p() && e('1');