#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getStakeholders();
cid=1
pid=1

查看项目集2的干系人信息 >> 测试17
根据干系人id倒序排序 >> 1
根据干系人id正序排序 >> 1
查看项目集2的干系人个数 >> 3

*/

$program = new Program('admin');

$t_Stakeholder = array(2, 'id_desc', 'id_asc', 2);

//var_dump($program->getByID1($t_Stakeholder[0]));die;
r($program->getByID1($t_Stakeholder[0]))   && p('0:realname') && e('测试17'); // 查看项目集2的干系人信息
r($program->getByOrder($t_Stakeholder[1])) && p()             && e('1');      // 根据干系人id倒序排序
r($program->getByOrder($t_Stakeholder[2])) && p()             && e('1');      // 根据干系人id正序排序
r($program->getCount5($t_Stakeholder[3]))  && p()             && e('3');      // 查看项目集2的干系人个数