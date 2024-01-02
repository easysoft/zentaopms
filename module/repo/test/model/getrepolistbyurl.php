#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getRepoListByUrl();
timeout=0
cid=1

- 使用空的url属性message @Url is empty.
- 使用错误的url属性message @No matched gitlab.
- 使用正确的url @return normal

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(4);

$repoModel = $tester->loadModel('repo');

$url    = '';
$result = $repoModel->getRepoListByUrl($url);
r($result) && p('message') && e('Url is empty.'); //使用空的url

$url    = 'http://192.168.1.161:51080/gitlab-instance-f9325ed1/azalea723test.git';
$result = $repoModel->getRepoListByUrl($url);
r($result) && p('message') && e('No matched gitlab.'); //使用错误的url

$url    = 'https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml.git';
$result = $repoModel->getRepoListByUrl($url);
if(!empty($result))
{
    if($result['status'] == 'fail' and $result['message'] != 'No matched gitlab.') $result = 'return normal';
    if(is_array($result) and $result['status'] == 'success') $result = 'return normal';
}
r($result) && p() && e('return normal'); //使用正确的url