#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';
su('admin');

/**

title=jobModel->getLastTagByRepo();
timeout=0
cid=16843

- 查询id为1的job的版本库的last tag @tag3
- 查询id为2的job的版本库的last tag @tag3
- 查询id为3的job的版本库的last tag @tag3
- 查询id为4的job的版本库的last tag @tag3
- 查询id为5的job的版本库的last tag @tag3

*/

zenData('pipeline')->gen(5);
zenData('job')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);

$job = new jobTest();
r($job->getLastTagByRepoTest(1)) && p() && e('tag3');  // 查询id为1的job的版本库的last tag
r($job->getLastTagByRepoTest(2)) && p() && e('tag3');  // 查询id为2的job的版本库的last tag
r($job->getLastTagByRepoTest(3)) && p() && e('tag3');  // 查询id为3的job的版本库的last tag
r($job->getLastTagByRepoTest(4)) && p() && e('tag3');  // 查询id为4的job的版本库的last tag
r($job->getLastTagByRepoTest(5)) && p() && e('tag3');  // 查询id为5的job的版本库的last tag
