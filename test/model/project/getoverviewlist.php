#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getOverviewList;
cid=1
pid=1

获取未开始的项目 >> 15
获取状态不为done和closed的项目 >> 15
根据项目ID获取项目详情 >> 11
获取不存在的项目 >> 0
按照ID正序获取项目列表 >> 1
按照项目名称倒序获取项目列表 >> 1

*/

$t = new Project('admin');

$P_status = array('wait', 'undone', 11, 10000, 'id_asc', 'name_desc');

r($t->getByStatus($P_status[0]))    && p()        && e('15'); // 获取未开始的项目
r($t->getByStatus($P_status[1]))    && p()        && e('15'); // 获取状态不为done和closed的项目
r($t->getByID($P_status[2]))        && p('11:id') && e('11'); // 根据项目ID获取项目详情
r($t->getByID($P_status[3]))        && p('id')    && e('0');  // 获取不存在的项目
r($t->getListByOrder($P_status[4])) && p()        && e('1');  //按照ID正序获取项目列表
r($t->getListByOrder($P_status[5])) && p()        && e('1');  // 按照项目名称倒序获取项目列表