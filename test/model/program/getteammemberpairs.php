#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getTeamMemberPairs();
cid=1
pid=1

获取项目集下所有团队成员 >> U:测试89
获取项目集下所有团队成员 >> 181

*/

$program = new Program('admin');

$t_Tnumber = array(1);

r($program->getById2($t_Tnumber[0]))  && p('user89') && e('U:测试89'); //获取项目集下所有团队成员
r($program->getCount6($t_Tnumber[0])) && p()         && e('181');      // 获取项目集下所有团队成员