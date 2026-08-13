#!/usr/bin/env php
<?php

/**
title=测试 spaceModel::getArtifactLibsBySpace();
timeout=0
cid=16024

- 查询无效的空间 @0
- 查询空间1下的制品库列表 @Array
- 查询空间2下的制品库列表 @Array
- 查询空间1下的第1个制品库ID @1
- 查询空间2下的第2个制品库ID @2
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

$artifact = zenData('ops_artifact_libs');
$artifact->id->range('1-4');
$artifact->spaceID->range('1,1,2,1');
$artifact->name->range('frontend-lib,backend-lib,release-lib,deleted-lib');
$artifact->type->range('npm,maven,file,raw');
$artifact->deleted->range('0,0,0,1');
$artifact->gen(4);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getArtifactLibsBySpaceCountTest(0))          && p() && e('0');             // 查询无效的空间
r($spaceTester->getArtifactLibsBySpaceCountTest(1))          && p() && e('2');             // 查询空间1下的制品库数量
r($spaceTester->getArtifactLibsBySpaceCountTest(2))          && p() && e('1');             // 查询空间2下的制品库数量
r($spaceTester->getArtifactLibsBySpaceFieldTest(1, 1, 'name')) && p() && e('frontend-lib'); // 查询空间1下的第1个制品库名称
r($spaceTester->getArtifactLibsBySpaceFieldTest(2, 3, 'type')) && p() && e('file');         // 查询空间2下的制品库类型
