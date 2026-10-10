#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getList();
timeout=0
cid=0

- 全列表含id=1和id=5
 - 第1条的name属性 @流水线1
 - 第5条的name属性 @流水线5
- repo级含id=1和id=5
 - 第1条的name属性 @流水线1
 - 第5条的name属性 @流水线5
- space级含id=3和id=4
 - 第3条的name属性 @流水线3
 - 第4条的name属性 @流水线4
- id正序首条第1条的name属性 @流水线1
- id倒序首条第5条的name属性 @流水线5
- 无权限的空间与私有代码库下的流水线被过滤 @2
- 只保留有权限的空间和代码库下的流水线
 - 第1条的name属性 @流水线1
 - 第3条的name属性 @流水线3
- 空间流水线只保留有权限的空间 @1
- 代码库流水线只保留有权限的代码库 @1
- 查看无权限的空间时返回空列表 @0
- 不属于任何空间的用户看不到任何流水线 @0

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
$pipeline->spaceID->range('1,1,1,2,2');
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

/* 权限数据：user1是私有空间1的成员，空间2私有且无成员；代码库1开放，代码库2私有且无成员，代码库3开放。 */
zenData('user')->gen(3);
zenData('group')->gen(0);
zenData('usergroup')->gen(0);

zenData('ops_space')->gen(0);
$space = zenData('ops_space');
$space->id->range('1,2');
$space->name->range('空间1,空间2');
$space->code->range('space1,space2');
$space->acl->range('private{2}');
$space->auth->range('extend{2}');
$space->deleted->range('0{2}');
$space->gen(2);

$spaceUser = zenData('ops_spaceuser');
$spaceUser->space->range('1');
$spaceUser->role->range('manager');
$spaceUser->account->range('user1');
$spaceUser->gen(1);

zenData('ops_repo')->gen(0);
$repo = zenData('ops_repo');
$repo->id->range('1-3');
$repo->spaceID->range('1,1,2');
$repo->name->range('代码库1,代码库2,代码库3');
$repo->gitUID->range('gituid000000000000000000000000000000000001,gituid000000000000000000000000000000000002,gituid000000000000000000000000000000000003');
$repo->acl->range('open,private,open');
$repo->status->range('active{3}');
$repo->deleted->range('0{3}');
$repo->gen(3);

zenData('ops_repouser')->gen(0);

su('admin');

$tester = new pipelineModelTest();

r($tester->getListTest()) && p('1:name;5:name') && e('流水线1;流水线5');            // 全列表含id=1和id=5
r($tester->getListTest(0, 0, 'repo')) && p('1:name;5:name') && e('流水线1;流水线5'); // repo级含id=1和id=5
r($tester->getListTest(0, 0, 'space')) && p('3:name;4:name') && e('流水线3;流水线4');// space级含id=3和id=4
r($tester->getListTest(0, 0, '', 'id_asc')) && p('1:name') && e('流水线1');         // id正序首条
r($tester->getListTest(0, 0, '', 'id_desc')) && p('5:name') && e('流水线5');        // id倒序首条

su('user1');
r(count($tester->getListTest())) && p() && e('2');                    // 无权限的空间与私有代码库下的流水线被过滤
r($tester->getListTest()) && p('1:name;3:name') && e('流水线1;流水线3'); // 只保留有权限的空间和代码库下的流水线
r(count($tester->getListTest(0, 0, 'space'))) && p() && e('1');       // 空间流水线只保留有权限的空间
r(count($tester->getListTest(0, 0, 'repo'))) && p() && e('1');        // 代码库流水线只保留有权限的代码库
r(count($tester->getListTest(2))) && p() && e('0');                   // 查看无权限的空间时返回空列表

su('user2');
r(count($tester->getListTest())) && p() && e('0');                    // 不属于任何空间的用户看不到任何流水线
