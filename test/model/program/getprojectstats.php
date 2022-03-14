#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getProjectStats();
cid=1
pid=1

查看当前项目集下所有未开始和进行中的项目的个数 >> 68
查看当前项目集下所有状态为进行中的项目的个数 >> 44
根据name倒序查看所有项目 >> 1
根据id倒序查看所有项目的个数 >> 1
查看所有项目（包含所属项目集名称） >> 项目1
查看当前用户参与的项目 >> 项目1
查看当前用户参与的项目的个数 >> 1

*/

$program = new Program('admin');

$t_statusNmb = array(0, 'doing', 'name_desc', 'id_desc', 0, 1 ,1, 'count');

r($program->getStatsByProgramID($t_statusNmb[0]))         && p()          && e('68');    // 查看当前项目集下所有未开始和进行中的项目的个数
r($program->getStatsByStatus($t_statusNmb[1]))            && p()          && e('44');    // 查看当前项目集下所有状态为进行中的项目的个数
r($program->getStatsByOrder($t_statusNmb[2]))             && p()          && e('1');     // 根据name倒序查看所有项目
r($program->getStatsByOrder($t_statusNmb[3]))             && p()          && e('1');     // 根据id倒序查看所有项目的个数
r($program->getStatsAddProgramTitle($t_statusNmb[4]))     && p('11:name') && e('项目1'); // 查看所有项目（包含所属项目集名称）
r($program->getStatsByInvolved($t_statusNmb[5]))          && p('11:name') && e('项目1'); // 查看当前用户参与的项目
r($program->getStatsByInvolved($t_statusNmb[6], $t_statusNmb[7])) && p()  && e('1');     // 查看当前用户参与的项目的个数