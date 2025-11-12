#!/usr/bin/env php
<?php

/**

title=测试 jobZen::getJobList();
timeout=0
cid=0

- 执行jobTest模块的getJobListTest方法，参数是0, '', 'id_asc', $pager 第1条的id属性 @1
- 执行jobTest模块的getJobListTest方法，参数是1, '', 'id_desc', $pager 第1条的repo属性 @1
- 执行jobTest模块的getJobListTest方法，参数是2, '', 'id_desc', $pager 第2条的repo属性 @2
- 执行jobTest模块的getJobListTest方法，参数是0, '', 'id_desc', $pager 第10条的engine属性 @GitLab
- 执行jobTest模块的getJobListTest方法，参数是0, '', 'id_desc', $pager 第5条的engine属性 @Jenkins
- 执行jobTest模块的getJobListTest方法，参数是0, '', 'id_desc', $pager 第10条的productName属性 @产品5
- 执行jobTest模块的getJobListTest方法，参数是0, '', 'id_desc', $pager 第10条的name属性 @SonarQube任务2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(5);

$repo = zenData('repo');
$repo->id->range('1-5');
$repo->name->range('测试仓库1,测试仓库2,测试仓库3,测试仓库4,测试仓库5');
$repo->SCM->range('Git');
$repo->deleted->range('0');
$repo->gen(5);

$pipeline = zenData('pipeline');
$pipeline->id->range('1-5');
$pipeline->name->range('Jenkins服务器1,Jenkins服务器2,Jenkins服务器3,GitLab服务器1,GitLab服务器2');
$pipeline->type->range('jenkins{3},gitlab{2}');
$pipeline->deleted->range('0');
$pipeline->gen(5);

$job = zenData('job');
$job->id->range('1-10');
$job->name->range('测试流水线1,测试流水线2,测试流水线3,Jenkins任务1,Jenkins任务2,GitLab流水线1,GitLab流水线2,GitLab流水线3,SonarQube任务1,SonarQube任务2');
$job->repo->range('1,2,3,4,5,1,2,3,4,5');
$job->product->range('1-5{2}');
$job->frame->range('phpunit{3},sonarqube{2},pytest{3},jest{2}');
$job->engine->range('jenkins{5},gitlab{5}');
$job->server->range('1-5{2}');
$job->pipeline->range('"/job/test_pipeline_1","/job/test_pipeline_2","/job/jenkins_job","{\"project\":1,\"reference\":\"master\"}","{\"project\":2,\"reference\":\"develop\"}","{\"project\":1,\"reference\":\"test\"}","{\"project\":2,\"reference\":\"main\"}","","",""');
$job->triggerType->range('commit{3},tag{2},schedule{3},action{2}');
$job->deleted->range('0');
$job->gen(10);

global $tester;
$tester->app->loadClass('pager', true);
$tester->app->rawModule = 'job';
$tester->app->rawMethod = 'browse';

su('admin');

$jobTest = new jobZenTest();
$pager = new pager(0, 20, 1);

r($jobTest->getJobListTest(0, '', 'id_asc', $pager)) && p('1:id') && e('1');
r($jobTest->getJobListTest(1, '', 'id_desc', $pager)) && p('1:repo') && e('1');
r($jobTest->getJobListTest(2, '', 'id_desc', $pager)) && p('2:repo') && e('2');
r($jobTest->getJobListTest(0, '', 'id_desc', $pager)) && p('10:engine') && e('GitLab');
r($jobTest->getJobListTest(0, '', 'id_desc', $pager)) && p('5:engine') && e('Jenkins');
r($jobTest->getJobListTest(0, '', 'id_desc', $pager)) && p('10:productName') && e('产品5');
r($jobTest->getJobListTest(0, '', 'id_desc', $pager)) && p('10:name') && e('SonarQube任务2');