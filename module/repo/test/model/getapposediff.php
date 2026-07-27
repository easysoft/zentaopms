#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel::getApposeDiff();
timeout=0
cid=18046

- 获取gitlab代码库对比信息文件第0条的fileName属性 @.gitlab-ci.yml
- 获取gitlab代码库比信息行信息
 - 属性oldStartLine @0
 - 属性newStartLine @1
- 获取svn代码库对比信息文件第0条的fileName属性 @README.md
- 获取svn代码库对比信息文件 @81
- 不存在diff数据的repo返回空数组 >> 1

*/

$repoTest = new repoModelTest();

r($repoTest->getApposeDiffTest(1, 'old', 'new'))        && p('0:fileName')                && e('.gitlab-ci.yml');
r($repoTest->getApposeDiffContentTest(1, 'old', 'new')) && p('oldStartLine,newStartLine') && e('0,1');
r($repoTest->getApposeDiffTest(4, '1', '2'))            && p('0:fileName')                && e('README.md');
r($repoTest->getApposeDiffLineCountTest(4, '1', '2'))   && p()                             && e('81');
r($repoTest->getApposeDiffTest(2, 'old', 'new'))        && p()                             && e('0');
