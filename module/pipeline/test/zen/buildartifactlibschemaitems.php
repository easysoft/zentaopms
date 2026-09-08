#!/usr/bin/env php
<?php

/**

title=测试 pipelineZen::buildArtifactLibSchemaItems();
timeout=0
cid=0

- 测试container类型树分组数 @2
- 测试container类型叶子value编码(space.code/code、repo{repoID}/code) @alpha/nacos,beta/redis,repo11/api
- 测试file类型叶子value沿用库id @3
- 测试scope=space仅含直挂库 @alpha/nacos,beta/redis
- 测试scope=repo仅含仓库级库 @repo11/api
- 测试空scope返回空 @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(2);
su('admin');

$space = zenData('ops_space');
$space->id->range('1,2');
$space->name->range('Alpha,Beta');
$space->code->range('alpha,beta');
$space->acl->range('open{2}');
$space->auth->range('extend{2}');
$space->deleted->range('0{2}');
$space->gen(2);

$spaceUser = zenData('ops_spaceuser');
$spaceUser->id->range('1-2');
$spaceUser->space->range('1,2');
$spaceUser->account->range('admin{2}');
$spaceUser->role->range('manager{2}');
$spaceUser->gen(2);

$repo = zenData('ops_repo');
$repo->id->range('11');
$repo->spaceID->range('1');
$repo->name->range('repo-a');
$repo->acl->range('open');
$repo->status->range('active');
$repo->deleted->range('0');
$repo->gen(1);

$lib = zenData('ops_artifact_libs');
$lib->id->range('1-7');
$lib->spaceID->range('1,1,1,2,0,1,1');
$lib->repoID->range('0,11,0,0,0,99,0');
$lib->type->range('container,container,file,container,container,container,container');
$lib->scope->range('space,repo,space,space,global,repo,space');
$lib->code->range('nacos,api,artifact-code,redis,global-img,ghost,nacos-deleted');
$lib->deleted->range('0{6},1');
$lib->gen(7);

$tester = new pipelineZenTest();

$r1 = $tester->buildArtifactLibSchemaItemsTest('space,repo', 'container');
$r2 = $tester->buildArtifactLibSchemaItemsTest('space,repo', 'file');
$r3 = $tester->buildArtifactLibSchemaItemsTest('space', 'container');
$r4 = $tester->buildArtifactLibSchemaItemsTest('repo', 'container');
$r5 = $tester->buildArtifactLibSchemaItemsTest('', 'container');

$groupCount1  = is_array($r1) ? $r1['groupCount'] : '';
$containerAll = is_array($r1) ? $r1['values'] : '';
$fileValues   = is_array($r2) ? $r2['values'] : '';
$spaceValues  = is_array($r3) ? $r3['values'] : '';
$repoValues   = is_array($r4) ? $r4['values'] : '';
$groupCount5  = is_array($r5) ? $r5['groupCount'] : '';

r($groupCount1) && p() && e('2'); // 测试container类型树分组数
r($containerAll) && p() && e('alpha/nacos,beta/redis,repo11/api'); // 测试container类型叶子value编码
r($fileValues) && p() && e('3'); // 测试file类型叶子value沿用库id
r($spaceValues) && p() && e('alpha/nacos,beta/redis'); // 测试scope=space仅含直挂库
r($repoValues) && p() && e('repo11/api'); // 测试scope=repo仅含仓库级库
r($groupCount5) && p() && e('0'); // 测试空scope返回空
