#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel::getListByRepoId();
timeout=0
cid=1

- 获取repo为1的name第1条的name属性 @这是一个Job1
- 获取repo为1的lastStatus第1条的lastStatus属性 @a
- 获取repo为100001的name第100001条的name属性 @0

*/
zdTable('job')->gen('1');

$job = new jobTest();

$repoIdList = array('1', '100001');

r($job->getListByRepoIDTest($repoIdList[0])) && p('1:name')       && e('这是一个Job1'); // 获取repo为1的name
r($job->getListByRepoIDTest($repoIdList[0])) && p('1:lastStatus') && e('a');            // 获取repo为1的lastStatus
r($job->getListByRepoIDTest($repoIdList[1])) && p('100001:name')  && e('0');            // 获取repo为100001的name