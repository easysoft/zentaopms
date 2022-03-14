#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getProjectList();
cid=1
pid=1

查看当前项目集下所有项目的个数 >> 90
查看当前项目集下所有状态为进行中的项目的个数 >> 44
根据name倒序查看所有项目 >> 1
根据id倒序查看所有项目的个数 >> 1
查看所有项目（包含所属项目集名称） >> 项目1
查看当前用户参与的项目 >> 项目1
查看当前用户参与的项目的个数 >> 1

*/

$countProjects = new Program('admin');

$t_proNumber = array(0, 'doing', 'name_desc', 'id_desc', 0, 1, 1, 'count');

r($countProjects->getListByProgramID($t_proNumber[0]))     && p() && e('90');             // 查看当前项目集下所有项目的个数
r($countProjects->getListByStatusNo($t_proNumber[1]))      && p() && e('44');             // 查看当前项目集下所有状态为进行中的项目的个数
r($countProjects->getListByOrderId($t_proNumber[2]))       && p() && e('1');              // 根据name倒序查看所有项目
r($countProjects->getListByOrderId($t_proNumber[3]))       && p() && e('1');              // 根据id倒序查看所有项目的个数
r($countProjects->getListAddProgramTitle($t_proNumber[4])) && p('11:name') && e('项目1'); // 查看所有项目（包含所属项目集名称）
r($countProjects->getListByInvolved($t_proNumber[5]))      && p('11:name') && e('项目1'); // 查看当前用户参与的项目
r($countProjects->getListByInvolved($t_proNumber[6], $t_proNumber[7]))  && p() && e('1'); // 查看当前用户参与的项目的个数