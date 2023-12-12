#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel->create();
timeout=0
cid=1

- 测试创建job name为空的情况第name条的0属性 @『流水线名称』不能为空。
- 测试创建job engine为空的情况第engine条的0属性 @『引擎』不能为空。
- 测试创建job triggerType为空的情况第triggerType条的0属性 @『触发方式』不能为空。
- 测试创建job name为《这是一个job007》的情况属性name @这是一个job007
- 测试创建job engine为gitlab的情况属性engine @gitlab
- 测试创建job triggerType为tag的情况属性triggerType @tag

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(1);

$job_name              = array('name' => '这是一个job007');
$job_engine            = array('engine' => 'gitlab');
$job_triggerType       = array('triggerType' => 'tag');
$job_name_blank        = array('name' => '');
$job_engine_blank      = array('engine' => '');
$job_triggerType_blank = array('triggerType' => '');

$job = new jobTest();

r($job->createObject($job_name_blank))        && p('name:0')        && e('『流水线名称』不能为空。');     // 测试创建job name为空的情况
r($job->createObject($job_engine_blank))      && p('engine:0')      && e('『引擎』不能为空。'); // 测试创建job engine为空的情况
r($job->createObject($job_triggerType_blank)) && p('triggerType:0') && e('『触发方式』不能为空。');   // 测试创建job triggerType为空的情况
r($job->createObject($job_name))              && p('name')          && e('这是一个job007');         // 测试创建job name为《这是一个job007》的情况
r($job->createObject($job_engine))            && p('engine')        && e('gitlab');                 // 测试创建job engine为gitlab的情况
r($job->createObject($job_triggerType))       && p('triggerType')   && e('tag');                    // 测试创建job triggerType为tag的情况