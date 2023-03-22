#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/job.class.php';
su('admin');

/**

title=jobModel->update();
cid=1
pid=1

测试更新job名称 >> Job更新成功
测试更新job引擎异常 >> SonarQube工具/框架仅在构建引擎为JenKins的情况下使用

*/

$jobID = 1;

$job_upName   = array('name' => '这是一个job11');
$job_upEngine = array('engine' => 'gitlab');

$job = new jobTest();

r($job->updateObject($jobID, $job_upName))   && p()    && e('Job更新成功');                                           //测试更新job名称
r($job->updateObject($jobID, $job_upEngine)) && p() && e('SonarQube工具/框架仅在构建引擎为JenKins的情况下使用');   //测试更新job引擎异常
