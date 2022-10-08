#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

/**

title=测试 personnelModel->getInvest();
cid=1
pid=1

项目集1下投入人员admin创建的bug数量 >> 测试,28
项目集1下投入人员admin待处理的bug数量 >> 测试,6
项目集1下投入人员po53创建的任务数量 >> 研发,0
项目集1下投入人员po53完成的任务数量 >> 研发,0

*/

$personnel = new personnelTest('admin');
$programID = array(1,2);

r($personnel->getInvestTest($programID[0])) && p('qa[admin]:role,createdBug')   && e('测试,28'); //项目集1下投入人员admin创建的bug数量
r($personnel->getInvestTest($programID[0])) && p('qa[admin]:role,pendingBug')   && e('测试,6');  //项目集1下投入人员admin待处理的bug数量
r($personnel->getInvestTest($programID[1])) && p('dev[po53]:role,createdTask')  && e('研发,0');  //项目集1下投入人员po53创建的任务数量
r($personnel->getInvestTest($programID[1])) && p('dev[po53]:role,finishedTask') && e('研发,0');  //项目集1下投入人员po53完成的任务数量
