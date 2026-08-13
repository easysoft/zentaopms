#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->prepareedit();
timeout=0
cid=0

- 执行test模块的prepareEditTest方法，参数是1  @1
- 执行test模块的prepareEditTest方法，参数是1  @1
- 执行test模块的prepareEditTest方法，参数是1  @1
- 执行test模块的prepareEditTest方法，参数是1  @1
- 执行test模块的prepareEditTest方法，参数是1  @1

*/

zenData('ops_repo')->gen(0);
zenData('ops_repouser')->gen(0);
zenData('ops_spaceuser')->gen(0);
zenData('entry')->gen(0);

$repo = zenData('ops_repo');
$repo->id->range('1');
$repo->spaceID->range('1');
$repo->product->range('1');
$repo->name->range('repo-zen-prepare');
$repo->scmType->range('git');
$repo->gitUID->range('repo-zen-prepare-gituid');
$repo->acl->range('private');
$repo->status->range('active');
$repo->deleted->range('0');
$repo->gen(1);

$repoUser = zenData('ops_repouser');
$repoUser->repo->range('1');
$repoUser->account->range('admin');
$repoUser->gen(1);

$spaceUser = zenData('ops_spaceuser');
$spaceUser->space->range('1');
$spaceUser->account->range('admin');
$spaceUser->role->range('manager');
$spaceUser->gen(1);

$entry = zenData('entry');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->gen(1);

class repoZenPrepareEditHttpClient
{
    public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
    {
        $space = (object)array('id' => 1, 'name' => 'repo test space', 'acl' => 'private', 'auth' => 'extend', 'createdDate' => '2026-08-06 00:00:00');
        if(strpos($url, '/spaces/1') !== false) return json_encode(array('code' => 'success', 'data' => $space));
        if(strpos($url, '/spaces') !== false)   return json_encode(array('code' => 'success', 'data' => array($space), 'listArgs' => (object)array('pageSize' => 1)));

        return json_encode(array('code' => 'success', 'data' => (object)array('id' => 1, 'path' => 'space/repo-zen-prepare', 'gitURL' => 'http://gitfox.test/space/repo-zen-prepare.git', 'gitSSHURL' => 'ssh://git@gitfox.test/space/repo-zen-prepare.git', 'importing' => false)));
    }
}

su('admin');
$test = new repoZenTest();
$oldHttpClient = common::$httpClient;
common::$httpClient = new repoZenPrepareEditHttpClient();

r($test->prepareEditTest(1)) && p() && e('1');
r($test->prepareEditTest(1)) && p() && e('1');
r($test->prepareEditTest(1)) && p() && e('1');
r($test->prepareEditTest(1)) && p() && e('1');
r($test->prepareEditTest(1)) && p() && e('1');

common::$httpClient = $oldHttpClient;
