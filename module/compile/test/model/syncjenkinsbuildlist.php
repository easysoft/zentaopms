#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 compileModel::syncJenkinsBuildList();
timeout=0
cid=15756

- 执行compileTest模块的syncJenkinsBuildListTest方法，参数是null, $job  @alse
- 执行compileTest模块的syncJenkinsBuildListTest方法，参数是$emptyJenkins, $job  @alse
- 执行compileTest模块的syncJenkinsBuildListTest方法，参数是$validJenkins, $job  @alse
- 执行$afterCount >= $beforeCount @rue
- 执行compileTest模块的syncJenkinsBuildListTest方法，参数是$validJenkins, $job  @alse

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$job = zenData('job');
$job->loadYaml('job', false, 2)->gen(5);

$compile = zenData('compile');
$compile->gen(3);

$pipeline = zenData('pipeline');
$pipeline->type->range('jenkins');
$pipeline->account->range('admin,user1,[],test,empty');
$pipeline->gen(5);

su('admin');

$compileTest = new compileModelTest();

// 准备测试数据
$job = new stdClass();
$job->id = 1;
$job->name = 'testJob';
$job->server = 1;
$job->pipeline = '{"name":"testPipeline"}';
$job->lastSyncDate = '2023-01-01 00:00:00';

$validJenkins = new stdClass();
$validJenkins->id = 1;
$validJenkins->type = 'jenkins';
$validJenkins->url = 'http://jenkins.test';
$validJenkins->account = 'admin';
$validJenkins->password = 'password';

$emptyJenkins = new stdClass();
$emptyJenkins->id = 2;
$emptyJenkins->type = 'jenkins';
$emptyJenkins->url = 'http://empty.test';
$emptyJenkins->account = '';
$emptyJenkins->password = '';

r($compileTest->syncJenkinsBuildListTest(null, $job)) && p() && e(false);
r($compileTest->syncJenkinsBuildListTest($emptyJenkins, $job)) && p() && e(false);
r($compileTest->syncJenkinsBuildListTest($validJenkins, $job)) && p() && e(false);
$beforeCount = count($compileTest->getListByJobIDTest($job->id));
$afterCount = count($compileTest->getListByJobIDTest($job->id));
r($afterCount >= $beforeCount) && p() && e(true);
r($compileTest->syncJenkinsBuildListTest($validJenkins, $job)) && p() && e(false);