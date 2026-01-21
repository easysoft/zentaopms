#!/usr/bin/env php
<?php

/**
title=测试 spaceModel::getReposBySpace();
timeout=0
cid=16030

- 查询无效的空间 @0
- 查询空间1下的代码库列表
 - 第1条的id属性 @2
 - 第1条的name属性 @723test
- 查询空间2下的代码库列表
 - 第2条的id属性 @2
 - 第2条的SCM属性 @Gitlab
- 查询空间1下的代码库总数 @1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('user')->gen(10);
zendata('repo')->gen(10);
zendata('ops_space')->gen(10);
zendata('ops_spaceuser')->gen(10);

su('admin');

global $tester;
$spaceModel = $tester->loadModel('space');

r($spaceModel->getReposBySpace(0))        && p()              && e('0'); //查询无效的空间
r($spaceModel->getReposBySpace(1))        && p('1:id;1:name') && e('1,723test'); //查询空间1下的代码库列表
r($spaceModel->getReposBySpace(2))        && p('2:id;2:SCM')  && e('2,Gitlab');  //查询空间2下的代码库列表
r(count($spaceModel->getReposBySpace(1))) && p()              && e('1'); //查询空间1下的代码库总数
