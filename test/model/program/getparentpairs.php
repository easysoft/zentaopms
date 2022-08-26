#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getParentPairs();
cid=1
pid=1

获取所有父项目集的数量 >> 7
获取瀑布类型父项目集的数量 >> 1
获取ID为1的父项目集的名称 >> /项目集1

*/

global $tester;
$tester->loadModel('program');
$program1 = $tester->program->getParentPairs();
$program2 = $tester->program->getParentPairs('waterfall');

r(count($program1)) && p()    && e('7');        // 获取所有父项目集的数量
r(count($program2)) && p()    && e('1');        // 获取瀑布类型父项目集的数量
r($program1)        && p('1') && e('/项目集1'); // 获取ID为1的父项目集的名称