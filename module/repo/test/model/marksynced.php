#!/usr/bin/env php
<?php

/**

title=测试 repoModel::markSynced();
timeout=0
cid=18087

- 步骤1：正常代码库ID属性synced @1
- 步骤2：不存在的代码库ID属性synced @0
- 步骤3：边界值0属性synced @0
- 步骤4：负数代码库ID属性synced @0
- 步骤5：验证fixCommit功能的代码库属性synced @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$repo = zenData('ops_repo');
$repo->id->range('1-4');
$repo->spaceID->range('1{4}');
$repo->product->range('1{4}');
$repo->name->range('测试代码库1,测试代码库2,测试代码库3,测试代码库4');
$repo->gitUID->range('mark-synced-uid-1,mark-synced-uid-2,mark-synced-uid-3,mark-synced-uid-4');
$repo->acl->range('private{4}');
$repo->status->range('active{4}');
$repo->synced->range('0{4}');
$repo->deleted->range('0{4}');
$repo->gen(4);

$repoUser = zenData('ops_repouser');
$repoUser->repo->range('1-4');
$repoUser->account->range('admin{4}');
$repoUser->gen(4);

zenData('ops_repobranch')->gen(0);

$history = zenData('ops_repohistory');
$history->id->range('1-3');
$history->repo->range('1,2,2');
$history->revision->range('r1,r2,r3');
$history->commit->range('10,20,30');
$history->comment->range('commit1,commit2,commit3');
$history->committer->range('admin{3}');
$history->time->range('10,12,11')->prefix('2024-01-01 ')->postfix(':00:00');
$history->gen(3);

$entry = zenData('entry');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->gen(1);

if(!class_exists('repoMarkSyncedHttpClient'))
{
    class repoMarkSyncedHttpClient
    {
        public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
        {
            return json_encode(array('code' => 'success', 'data' => array('id' => 1, 'path' => 'space/repo', 'gitURL' => 'http://gitfox.test/space/repo.git', 'importing' => false)));
        }
    }
}

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();
$oldHttpClient = common::$httpClient;
common::$httpClient = new repoMarkSyncedHttpClient();

r($repoTest->markSyncedTest(1)) && p('synced') && e('1');    // 步骤1：正常代码库ID
r($repoTest->markSyncedTest(999)) && p('synced') && e('0');  // 步骤2：不存在的代码库ID
r($repoTest->markSyncedTest(0)) && p('synced') && e('0');    // 步骤3：边界值0
r($repoTest->markSyncedTest(-1)) && p('synced') && e('0');   // 步骤4：负数代码库ID
r($repoTest->markSyncedTest(2)) && p('synced') && e('1');    // 步骤5：验证fixCommit功能的代码库

common::$httpClient = $oldHttpClient;
