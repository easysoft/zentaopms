#!/usr/bin/env php
<?php

/**

title=svnModel->saveCommits();
timeout=0
cid=18721

- 没有提交信息
 - 属性id @1
 - 属性commits @1
- 没有关联信息
 - 属性id @1
 - 属性commits @2
- 有关联信息
 - 属性id @1
 - 属性commits @2
- 一条没有关联信息，一条有关联信息
 - 属性id @1
 - 属性commits @3
- 一条没有关联信息，一条有关联信息，有关联构建
 - 属性id @1
 - 属性commits @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('repo')->loadYaml('repo')->gen(1);
zenData('repohistory')->loadYaml('repohistory')->gen(1);
su('admin');

$svn = new svnModelTest();

r($svn->saveCommitsTest(1, 'empty')) && p('id,commits') && e('1,1'); // 没有提交信息
r($svn->saveCommitsTest(1, 'nolink')) && p('id,commits') && e('1,2'); // 没有关联信息
r($svn->saveCommitsTest(1, 'linked')) && p('id,commits') && e('1,2'); // 有关联信息

r($svn->saveCommitsTest(1, 'nolink|linked'))     && p('id,commits') && e('1,3'); // 一条没有关联信息，一条有关联信息
r($svn->saveCommitsTest(1, 'nolink|linked|job')) && p('id,commits') && e('1,3'); // 一条没有关联信息，一条有关联信息，有关联构建