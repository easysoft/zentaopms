#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel::getListByTriggerType();
timeout=0
cid=1

- 获取trigger type为tag的repo为1的name第1条的name属性 @这是一个Job1
- 获取trigger type为commit的repo为2的name第2条的name属性 @这是一个Job2

*/
zdTable('job')->gen(5);

$job = new jobTest();

$triggerType = array('tag', 'commit');
$repoIdList  = array('1', '2', '100001');

r($job->getListByTriggerTypeTest($triggerType[0], $repoIdList)) && p('1:name') && e('这是一个Job1'); // 获取trigger type为tag的repo为1的name
r($job->getListByTriggerTypeTest($triggerType[1], $repoIdList)) && p('2:name') && e('这是一个Job2'); // 获取trigger type为commit的repo为2的name