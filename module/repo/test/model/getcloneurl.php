#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getCloneUrl();
timeout=0
cid=18051

- 执行repo模块的getCloneUrlAvailableTest方法，参数是1 @1
- 执行repo模块的getCloneUrlAvailableTest方法，参数是2, 'ssh' @1
- 执行repo模块的getCloneUrlAvailableTest方法，参数是3 @1
- 执行repo模块的getCloneUrlAvailableTest方法，参数是4 @1
- 获取空项目 @empty

*/

$repoData = zenData('ops_repo');
$repoData->id->range('1-4');
$repoData->spaceID->range('1{4}');
$repoData->product->range('1{4}');
$repoData->name->range('testHtml,Monitoring,unittest,testSvn');
$repoData->scmType->range('git,git,git,svn');
$repoData->gitUID->range('clone-url-uid-1,clone-url-uid-2,clone-url-uid-3,clone-url-uid-4');
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

if(!class_exists('repoGetCloneUrlHttpClient'))
{
    class repoGetCloneUrlHttpClient
    {
        public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
        {
            return json_encode(array('code' => 'success', 'data' => array('id' => 1, 'path' => 'space/repo', 'gitURL' => 'http://gitfox.test/space/repo.git', 'gitSSHURL' => 'ssh://git@gitfox.test/space/repo.git', 'importing' => false)));
        }
    }
}

$repo          = new repoModelTest();
$oldHttpClient = common::$httpClient;
common::$httpClient = new repoGetCloneUrlHttpClient();

r($repo->getCloneUrlAvailableTest(1))        && p() && e('1');
r($repo->getCloneUrlAvailableTest(2, 'ssh')) && p() && e('1');
r($repo->getCloneUrlAvailableTest(3))        && p() && e('1');
r($repo->getCloneUrlAvailableTest(4))        && p() && e('1');
r($repo->getCloneUrlTest(0)) && p() && e('empty');

common::$httpClient = $oldHttpClient;
