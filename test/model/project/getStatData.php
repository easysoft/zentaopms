#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::checkHasContent;
cid=1
pid=1

统计id=13的项目bug数量 >> 3
任务数量 >> 13
暂停数量 >> 2
进行的数量 >> 2
完成的数量 >> 0

*/

$project = new Project('admin');

r($project->getStatData(13)) && p('bugCount')       && e('3');  //统计id=13的项目bug数量
r($project->getStatData(13)) && p('taskCount')      && e('13'); //任务数量
r($project->getStatData(13)) && p('waitCount')      && e('2');  //暂停数量
r($project->getStatData(13)) && p('doingCount')     && e('2');  //进行的数量
r($project->getStatData(13)) && p('finishedCount')  && e('0');  //完成的数量