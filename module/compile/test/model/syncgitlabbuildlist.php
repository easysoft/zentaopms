#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('job')->loadYaml('job')->gen(6);
zenData('compile')->gen(6);
zenData('pipeline')->gen(6);
su('admin');

/**

title=测试 compileModel->syncGitlabBuildList();
timeout=0
cid=1

- 执行 @1
- 调用gitlab接口之前的获取不到ID为50的compile。属性50 @~~
- 调用gitlab接口之后的能获取到ID为50的compile。第50条的name属性 @这是一个Job2

*/

$tester->loadModel('compile');
$gitlabPairs = $tester->loadModel('pipeline')->getList('gitlab');
$job = $tester->loadModel('job')->getByID(2);
$server = zget($gitlabPairs, $job->server);
r(1) && p() && e('1');
r($tester->compile->getListByJobID(2)) && p('50')      && e('~~');            //调用gitlab接口之前的获取不到ID为50的compile。
$tester->compile->syncGitlabBuildList($server, $job);
r($tester->compile->getListByJobID(2)) && p('50:name') && e('这是一个Job2');  //调用gitlab接口之后的能获取到ID为50的compile。