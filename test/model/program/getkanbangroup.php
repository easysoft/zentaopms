#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getKanbanGroup();
cid=1
pid=1

查看当前用户看板的其它项目 >> 项目集1

*/

$t_getKanban = array('admin', 'my', 'others');

$program = new Program($t_getKanban[0]);

r($program->getKanbanGroup()[$t_getKanban[1]]) && p('0:name') && e(''); //查看当前用户看板的所属项目
r($program->getKanbanGroup()[$t_getKanban[2]]) && p('0:name') && e('项目集1'); //查看当前用户看板的其它项目