#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getTeamMemberPairs();
cid=1
pid=1

获取项目集下所有团队成员 >> P:产品经理92
获取项目集下所有团队成员 >> 19

*/

$program = new Program('admin');

$t_Tnumber = array(1);

//var_dump($program->getById2($t_Tnumber[0]));die;
r($program->getById2($t_Tnumber[0]))  && p('pm92') && e('P:产品经理92'); //获取项目集下所有团队成员
r($program->getCount6($t_Tnumber[0])) && p()         && e('19');      // 获取项目集下所有团队成员