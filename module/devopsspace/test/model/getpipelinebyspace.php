#!/usr/bin/env php
<?php

/**
title=测试 devopsspaceModel::getPipelineBySpace();
timeout=0
cid=0

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

zendata('user')->gen(10);
zendata('repo')->gen(10);
zendata('job')->gen(10);
zendata('ops_space')->gen(10);
zendata('ops_spaceuser')->gen(10);

su('admin');

global $tester;
$devopsspaceModel = $tester->loadModel('devopsspace');

r($devopsspaceModel->getPipelineBySpace(0))        && p()                && e('0'); //查询无效的空间
r($devopsspaceModel->getPipelineBySpace(1))        && p('1:id;1:name')   && e('1,这是一个Job1'); //查询空间1下的流水线列表
r($devopsspaceModel->getPipelineBySpace(2))        && p('2:id;2:engine') && e('2,gitlab'); //查询空间2下的流水线列表
r(count($devopsspaceModel->getPipelineBySpace(1))) && p()                && e('1'); //查询空间1下的流水线总数
