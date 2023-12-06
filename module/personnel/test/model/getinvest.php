#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('project')->gen(50);
zdTable('user')->gen(20);
zdTable('product')->gen(10);
zdTable('bug')->gen(50);
zdTable('task')->gen(50);
zdTable('team')->gen(50);

su('admin');

/**

title=测试 personnelModel->getInvest();
cid=1
pid=1

*/

$personnel = new personnelTest();
$programID = array(1, 2);

r($personnel->getInvestTest($programID[0])) && p('qa[admin]:role,createdBug')    && e('测试,3');  //项目集1下投入人员admin创建的bug数量
r($personnel->getInvestTest($programID[0])) && p('qa[admin]:role,pendingBug')    && e('测试,3');  //项目集1下投入人员admin待处理的bug数量
r($personnel->getInvestTest($programID[1])) && p('qa[user3]:role,createdTask')   && e('测试,0');  //项目集1下投入人员po53创建的任务数量
r($personnel->getInvestTest($programID[1])) && p('qa[user3]:role,finishedTask')  && e('测试,0');  //项目集1下投入人员po53完成的任务数量
