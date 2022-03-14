#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::setTreePath();
cid=1
pid=1

查找id=11的项目集的path >> 12,
查找不存在的id=1000的项目集的path >> 0

*/

$program = new Program('admin');

$findId = array(12, 1000);

r($program->setTreePath($findId[0])) && p('path') && e('12,'); // 查找id=11的项目集的path
#r($program->setTreePath($findId[1])) && p('path') && e('0');   // 查找不存在的id=1000的项目集的path