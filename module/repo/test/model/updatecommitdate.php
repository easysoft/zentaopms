#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel::updateCommitDate();
timeout=0
cid=18112

- 执行repo模块的updateCommitDateSuccessTest方法，参数是1  @1
- 执行repo模块的updateCommitDateSuccessTest方法，参数是3  @1
- 执行repo模块的updateCommitDateSuccessTest方法，参数是999  @1
- 执行repo模块的updateCommitDateSuccessTest方法，参数是4  @1
- 执行repo模块的updateCommitDateSuccessTest方法  @1

*/

$repoData = zenData('ops_repo');
$repoData->id->range('1-4');
$repoData->spaceID->range('1{4}');
$repoData->product->range('1{4}');
$repoData->name->range('testHtml,project1,unittest,testSvn');
$repoData->scmType->range('git{3},svn');
$repoData->gitUID->range('commitdate-gituid-1,commitdate-gituid-2,commitdate-gituid-3,commitdate-gituid-4');
$repoData->acl->range('private{4}');
$repoData->status->range('active{4}');
$repoData->deleted->range('0{4}');
$repoData->gen(4);

$repoUser = zenData('ops_repouser');
$repoUser->repo->range('1-4');
$repoUser->account->range('admin{4}');
$repoUser->gen(4);

$entry = zenData('entry');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->gen(1);

class repoUpdateCommitDateHttpClient
{
    public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
    {
        return json_encode(array('code' => 'success', 'data' => array('id' => 1, 'path' => 'space/repo', 'gitURL' => 'http://gitfox.test/space/repo.git', 'gitSSHURL' => 'ssh://git@gitfox.test/space/repo.git', 'importing' => false)));
    }
}

$repo = new repoModelTest();
$oldHttpClient = common::$httpClient;
common::$httpClient = new repoUpdateCommitDateHttpClient();

r($repo->updateCommitDateSuccessTest(1)) && p() && e('1');
r($repo->updateCommitDateSuccessTest(3)) && p() && e('1');
r($repo->updateCommitDateSuccessTest(999)) && p() && e('1');
r($repo->updateCommitDateSuccessTest(4)) && p() && e('1');
r($repo->updateCommitDateSuccessTest(0)) && p() && e('1');

common::$httpClient = $oldHttpClient;
