#!/usr/bin/env php
<?php

/**
title=测试 spaceModel::getArtifactReposBySpace();
timeout=0
cid=16024

- 查询无效的空间 @0
- 查询空间1下的代码库列表
 - 第1条的id属性 @1
 - 第1条的name属性 @artifact1
- 查询空间2下的代码库列表
 - 第2条的id属性 @2
 - 第2条的name属性 @artifact2
- 查询空间1下的代码库总数 @1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('user')->gen(10);
zendata('artifactrepo')->gen(10);
zendata('ops_space')->gen(10);
zendata('ops_spaceuser')->gen(10);

su('admin');

global $tester;
$spaceModel = $tester->loadModel('space');

r($spaceModel->getArtifactReposBySpace(0))        && p()              && e('0'); //查询无效的空间
r($spaceModel->getArtifactReposBySpace(1))        && p('1:id;1:name') && e('1,artifact1'); //查询空间1下的代码库列表
r($spaceModel->getArtifactReposBySpace(2))        && p('2:id;2:name') && e('2,artifact2');  //查询空间2下的代码库列表
r(count($spaceModel->getArtifactReposBySpace(1))) && p()              && e('1'); //查询空间1下的代码库总数
