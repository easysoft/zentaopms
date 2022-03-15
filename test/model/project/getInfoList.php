#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getInfoList;
cid=1
pid=1

查询正在进行的项目 >> 0
查询等待状态的项目 >> 项目1
查询暂停状态的项目 >> 0
查询关闭状态的项目 >> 0
查询所有状态的项目 >> 项目1

*/

$project = new Project('admin');

$t_status = array('doing', 'wait', 'suspended', 'closed', 'all');

//var_dump($project->getInfoList('all'));die;
r($project->getInfoList($t_status[0])) && p() && e('0');               //查询正在进行的项目
r($project->getInfoList($t_status[1])) && p('11:name') && e('项目1');  //查询等待状态的项目
r($project->getInfoList($t_status[2])) && p() && e('0');               //查询暂停状态的项目
r($project->getInfoList($t_status[3])) && p() && e('0');               //查询关闭状态的项目
r($project->getInfoList($t_status[4])) && p('11:name') && e('项目1');  //查询所有状态的项目