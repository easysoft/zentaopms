#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('job')->config('job')->gen(6);
zdTable('compile')->gen(6);
zdTable('pipeline')->gen(6);
su('admin');

/**

title=测试 compileModel->syncJenkinsBuildList();
cid=1
pid=1

- 调用jenkins接口之前job为1的compile数量。 @1
- 调用jenkins接口之后job为1的compile数量。 @17

*/

$tester->loadModel('compile');
$jenkinsPairs = $tester->loadModel('pipeline')->getList('jenkins');
$job = $tester->loadModel('job')->getByID(1);
$server = zget($jenkinsPairs, $job->server);
r(count($tester->compile->getListByJobID(1))) && p() && e(1);   //调用jenkins接口之前job为1的compile数量。
$tester->compile->syncJenkinsBuildList($server, $job);
r(count($tester->compile->getListByJobID(1))) && p() && e(17);  //调用jenkins接口之后job为1的compile数量。
