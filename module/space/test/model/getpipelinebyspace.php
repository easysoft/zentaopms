#!/usr/bin/env php
<?php

/**
title=测试 spaceModel::getPipelineBySpace();
timeout=0
cid=16029

- 查询无效的空间 @0
- 查询空间1下的流水线列表
 - 第1条的id属性 @1
 - 第1条的name属性 @这是一个Job1
- 查询空间2下的流水线列表
 - 第2条的id属性 @2
 - 第2条的engine属性 @gitlab
- 查询空间1下的流水线总数 @1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

$pipeline = zenData('ops_pipeline');
$pipeline->id->range('1-4');
$pipeline->spaceID->range('1,1,2,1');
$pipeline->name->range('build-one,deploy-one,test-two,deleted-one');
$pipeline->engine->range('gitfox,jenkins,gitlab,gitfox');
$pipeline->deleted->range('0,0,0,1');
$pipeline->gen(4);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getPipelineBySpaceCountTest(0))            && p() && e('0');          // 查询无效的空间
r($spaceTester->getPipelineBySpaceCountTest(1))            && p() && e('2');          // 查询空间1下的流水线数量
r($spaceTester->getPipelineBySpaceFieldTest(1, 1, 'name'))   && p() && e('build-one');  // 查询空间1下的第1条流水线名称
r($spaceTester->getPipelineBySpaceFieldTest(2, 3, 'engine')) && p() && e('gitlab');     // 查询空间2下的流水线引擎
r($spaceTester->getPipelineBySpaceFieldTest(1, 2, 'engine')) && p() && e('jenkins');    // 查询空间1下的第2条流水线引擎
