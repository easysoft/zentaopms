#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getTopPairs();
cid=1
pid=1

查看id=1的父项目集 >> 项目集1
查看父项目集的个数 >> 6

*/

$program = new Program('admin');

$t_checkId = 'count';

r($program->getTopPairs())        && p('1') && e('项目集1'); // 查看id=1的父项目集
r($program->getTopPairs($t_checkId)) && p()    && e('6'); // 查看父项目集的个数