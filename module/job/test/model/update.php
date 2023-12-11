#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel->update();
timeout=0
cid=1

- 测试job名称为空第name条的0属性 @『流水线名称』不能为空。
- 测试更新job名称属性name @这是一个job11
- 测试更新job引擎异常第frame条的0属性 @SonarQube工具/框架仅在构建引擎为JenKins的情况下使用
- 测试更新triggerType为schedule的job定时任务时间属性atDay @6
- 测试更新triggerType为tag的job定时任务时间属性atDay @3

*/

zdTable('job')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$jobID = 1;

$job_upName    = array('name' => '这是一个job11');
$job_emptyName = array('name' => '');
$job_upEngine  = array('engine' => 'gitlab');

$job = new jobTest();

r($job->updateObject($jobID, $job_emptyName)) && p('name:0')  && e('『流水线名称』不能为空。');                            //测试job名称为空
r($job->updateObject($jobID, $job_upName))    && p('name')    && e('这是一个job11');                                       //测试更新job名称
r($job->updateObject($jobID, $job_upEngine))  && p('frame:0') && e('SonarQube工具/框架仅在构建引擎为JenKins的情况下使用'); //测试更新job引擎异常
$_POST['atDay'] = array('6');
r($job->updateObject(3)) && p('atDay') && e('6');                                         //测试更新triggerType为schedule的job定时任务时间
$_POST['atDay'] = array('5');
r($job->updateObject(4)) && p('atDay') && e('3');                                         //测试更新triggerType为tag的job定时任务时间