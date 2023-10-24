#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';
su('admin');

zdTable('project')->gen(50);
zdTable('user')->gen(20);

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

r($personnel->getInvestTest($programID[0])) && p('qa[admin]:role,createdBug')    && e('测试,0'); //项目集1下投入人员admin创建的bug数量
r($personnel->getInvestTest($programID[0])) && p('qa[admin]:role,pendingBug')    && e('测试,0');  //项目集1下投入人员admin待处理的bug数量
r($personnel->getInvestTest($programID[1])) && p('qa[user3]:role,createdTask')   && e('测试,0');  //项目集1下投入人员po53创建的任务数量
r($personnel->getInvestTest($programID[1])) && p('qa[user3]:role,finishedTask')  && e('测试,0');  //项目集1下投入人员po53完成的任务数量
