#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 repoModel::getMatchedReposByUrl();
timeout=0
cid=1

- 使用错误的url @0
- 使用正确的url
 - 第0条的gitlab属性 @1
 - 第0条的project属性 @2

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(4);

$repoModel = $tester->loadModel('repo');

$url = 'http://192.168.1.161:51080/gitlab-instance-f9325ed1/azalea723test.git';
r($repoModel->getMatchedReposByUrl($url)) && p() && e('0'); //使用错误的url

$url = 'https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml.git';
r($repoModel->getMatchedReposByUrl($url)) && p('0:gitlab,project') && e('1,2'); //使用正确的url