#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel::getApposeDiff();
timeout=0
cid=1

- 获取gitlab代码库对比信息文件第0条的fileName属性 @.gitlab-ci.yml
- 获取gitlab代码库比信息行信息
 - 属性oldStartLine @0
 - 属性newStartLine @1
- 获取svn代码库对比信息文件第0条的fileName属性 @README.md
- 获取svn代码库对比信息文件 @81

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$repoTest = new repoTest();
$gitlabID    = 1;
$oldRevision = 'c808480afe22d3a55d94e91c59a8f3170212ade0';
$newRevision = '1b9405639ddef9585b3743b0637b4f79775409b7';

$result = $repoTest->getApposeDiffTest($gitlabID, $oldRevision, $newRevision);
r($result)                 && p('0:fileName')                && e('.gitlab-ci.yml'); //获取gitlab代码库对比信息文件
r($result[1]->contents[0]) && p('oldStartLine,newStartLine') && e('0,1'); //获取gitlab代码库比信息行信息

$svnID  = 4;
$oldRevision = '1';
$newRevision = '2';
$result = $repoTest->getApposeDiffTest($svnID, $oldRevision, $newRevision);
r($result)                               && p('0:fileName') && e('README.md'); //获取svn代码库对比信息文件
r(count($result[0]->contents[0]->lines)) && p()             && e('81'); //获取svn代码库对比信息文件