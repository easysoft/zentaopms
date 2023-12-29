#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('job')->config('job')->gen(6);
zdTable('compile')->gen(6);
zdTable('pipeline')->gen(6);
su('admin');

/**

title=测试 compileModel->syncGitlabBuildList();
cid=1
pid=1

- 调用gitlab接口之前的compile数量。 @1
- 调用gitlab接口之后的compile数量。 @50

*/

$tester->loadModel('compile');
$gitlabPairs = $tester->loadModel('pipeline')->getList('gitlab');
$job = $tester->loadModel('job')->getByID(2);
$server = zget($gitlabPairs, $job->server);
r(count($tester->compile->getListByJobID(2))) && p() && e(1);   //调用gitlab接口之前的compile数量。
$tester->compile->syncGitlabBuildList($server, $job);
r(count($tester->compile->getListByJobID(2))) && p() && e(50);  //调用gitlab接口之后的compile数量。
