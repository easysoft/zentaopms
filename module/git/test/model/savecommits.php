#!/usr/bin/env php
<?php

/**

title=gitModel->saveCommits();
timeout=0
cid=16552

- 没有提交信息
 - 属性id @0
 - 属性commits @0
- 没有关联信息
 - 属性id @1
 - 属性commits @0
- 有关联信息
 - 属性id @1
 - 属性commits @0
- 一条没有关联信息，一条有关联信息
 - 属性id @1
 - 属性commits @0
- 一条没有关联信息，一条有关联信息，有关联构建
 - 属性id @1
 - 属性commits @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('repo')->loadYaml('repo')->gen(1);
zenData('repohistory')->gen(0);
su('admin');

$git = new gitModelTest();

r($git->saveCommitsTest(1, 'empty')) && p('id,commits') && e('0,0'); // 没有提交信息
r($git->saveCommitsTest(1, 'nolink')) && p('id,commits') && e('1,0'); // 没有关联信息
r($git->saveCommitsTest(1, 'linked')) && p('id,commits') && e('1,0'); // 有关联信息

r($git->saveCommitsTest(1, 'nolink|linked'))     && p('id,commits') && e('1,0'); // 一条没有关联信息，一条有关联信息
r($git->saveCommitsTest(1, 'nolink|linked|job')) && p('id,commits') && e('1,0'); // 一条没有关联信息，一条有关联信息，有关联构建