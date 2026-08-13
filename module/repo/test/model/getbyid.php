#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getByID();
timeout=0
cid=18048

- 测试步骤1：正常获取存在的repo对象
 - 属性id @1
 - 属性name @testHtml
 - 属性SCM @Gitlab
- 测试步骤2：验证repo对象的基本属性属性serviceProject @1
- 测试步骤3：测试不存在的repoID @0
- 测试步骤4：测试无效的repoID(0) @0
- 测试步骤5：测试负数repoID @0
- 测试步骤6：验证Gitea仓库信息
 - 属性name @unittest
 - 属性SCM @Gitea
- 测试步骤7：验证SVN仓库加密信息
 - 属性account @admin
 - 属性encrypt @base64

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$repo = zenData('ops_repo');
$repo->id->range('1-4');
$repo->spaceID->range('1{4}');
$repo->product->range('1{4}');
$repo->name->range('testHtml,project1,unittest,testSvn');
$repo->scmType->range('git{3},svn');
$repo->gitUID->range('getbyid-gituid-1,getbyid-gituid-2,getbyid-gituid-3,getbyid-gituid-4');
$repo->acl->range('private{4}');
$repo->status->range('active{4}');
$repo->deleted->range('0{4}');
$repo->gen(4);

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

class repoGetByIDHttpClient
{
    public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
    {
        return json_encode(array('code' => 'success', 'data' => array('id' => 1, 'path' => 'space/repo', 'gitURL' => 'http://gitfox.test/space/repo.git', 'gitSSHURL' => 'ssh://git@gitfox.test/space/repo.git', 'importing' => false)));
    }
}

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();
$oldHttpClient = common::$httpClient;
common::$httpClient = new repoGetByIDHttpClient();

r($repoTest->getByIDTest(1)) && p('id,name,scmType') && e('1,testHtml,git'); // 测试步骤1：正常获取存在的repo对象
r($repoTest->getByIDTest(2)) && p('spaceID,product') && e('1,1'); // 测试步骤2：验证repo对象的基本属性
r($repoTest->getByIDTest(999)) && p() && e('0'); // 测试步骤3：测试不存在的repoID
r($repoTest->getByIDTest(0)) && p() && e('0'); // 测试步骤4：测试无效的repoID(0)
r($repoTest->getByIDTest(-1)) && p() && e('0'); // 测试步骤5：测试负数repoID
r($repoTest->getByIDTest(3)) && p('name,gitUID') && e('unittest,getbyid-gituid-3'); // 测试步骤6：验证GitFox仓库信息
r($repoTest->getByIDTest(4)) && p('name,scmType') && e('testSvn,svn'); // 测试步骤7：验证SVN仓库信息

common::$httpClient = $oldHttpClient;
