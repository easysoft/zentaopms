#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel::getTriggerConfig();
timeout=0
cid=1

- 获取triggerType为tag的trigger config @目录改动(/module/caselib)
- 获取triggerType为commit的trigger config @提交注释包含关键字(b)
- 获取triggerType为schedule的trigger config @定时计划(星期二, 22)

*/
zdTable('job')->gen(5);

$job = new jobTest();

$jobIdList = array(1, 2, 3);

r($job->getTriggerConfigTest($jobIdList[0])) && p() && e('目录改动(/module/caselib)'); // 获取triggerType为tag的trigger config
r($job->getTriggerConfigTest($jobIdList[1])) && p() && e('提交注释包含关键字(b)');     // 获取triggerType为commit的trigger config
r($job->getTriggerConfigTest($jobIdList[2])) && p() && e('定时计划(星期二, 22)');     // 获取triggerType为schedule的trigger config