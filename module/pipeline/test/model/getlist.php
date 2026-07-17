#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getList();
timeout=0
cid=0

- 测试获取所有流水线列表 @流水线1;流水线5
- 测试获取代码库级流水线列表 @流水线1;流水线5
- 测试获取空间级流水线列表 @流水线3;流水线4
- 测试按id正序获取第1条name属性 @流水线1
- 测试按id倒序获取第1条name属性 @流水线5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app;
$app->rawModule = 'pipeline';
$app->rawMethod = 'browse';

/* 使用 zendata 准备测试数据 */
$pipeline = zenData('ops_pipeline');
$pipeline->id->range('1-5');
$pipeline->name->range('流水线1,流水线2,流水线3,流水线4,流水线5');
$pipeline->engine->range('gitfox,gitlab,jenkins,gitfox,gitlab');
$pipeline->providerID->range('1,1,0,0,1');
$pipeline->scope->range('repo,repo,space,space,repo');
$pipeline->spaceID->range('1,1,1,1,2');
$pipeline->repoID->range('1,2,0,0,3');
$pipeline->status->range('active{5}');
$pipeline->defaultBranch->range('main{5}');
$pipeline->createdBy->range('admin{5}');
$pipeline->createdDate->setNull();
$pipeline->deleted->range('0');
$pipeline->gen(5);

$pipelineContent = zenData('ops_pipeline_content');
$pipelineContent->id->range('1-5');
$pipelineContent->pipelineID->range('1-5');
$pipelineContent->version->range('1{5}');
$pipelineContent->createdBy->range('admin{5}');
$pipelineContent->gen(5);

$provider = zenData('ops_provider');
$provider->id->range('1');
$provider->type->range('gitlab');
$provider->name->range('GitLab');
$provider->deleted->range('0');
$provider->gen(1);

su('admin');

$tester = new pipelineModelTest();

r($tester->getListTest()) && p('1:name;5:name') && e('流水线1;流水线5');            // 全列表含id=1和id=5
r($tester->getListTest(0, 0, 'repo')) && p('1:name;5:name') && e('流水线1;流水线5'); // repo级含id=1和id=5
r($tester->getListTest(0, 0, 'space')) && p('3:name;4:name') && e('流水线3;流水线4');// space级含id=3和id=4
r($tester->getListTest(0, 0, '', 'id_asc')) && p('1:name') && e('流水线1');         // id正序首条
r($tester->getListTest(0, 0, '', 'id_desc')) && p('5:name') && e('流水线5');        // id倒序首条
