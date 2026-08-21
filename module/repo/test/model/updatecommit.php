#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

if(!defined('TABLE_JOB')) define('TABLE_JOB', 'zt_job');

/**
title=测试 repoModel->updateCommit();
timeout=0
cid=18110

- Git 类型版本库属性scmType,status @git,exception
- SVN 类型版本库属性scmType,status @svn,exception
- 非 Git/SVN 类型版本库属性scmType,status @other,success
- 不存在 repoID 属性repoID,status @999,repoNotFound
- 非法 repoID 属性repoID,status @0,repoNotFound
- 带 branchID 参数调用属性scmType,branchID,status @git,main,exception
- 带 objectID 参数调用属性scmType,objectID,status @git,123,exception

*/

zenData('ops_repo')->gen(0);
zenData('ops_repobranch')->gen(0);
zenData('ops_repouser')->gen(0);
zenData('job')->gen(0);

$repo = zenData('ops_repo');
$repo->id->range('1-4');
$repo->spaceID->range('1{4}');
$repo->product->range('1{4}');
$repo->name->range('git-repo-one,git-repo-two,svn-repo,other-repo');
$repo->scmType->range('git,git,svn,other');
$repo->gitUID->range('update-commit-gituid-1,update-commit-gituid-2,update-commit-gituid-3,update-commit-gituid-4');
$repo->acl->range('private{4}');
$repo->status->range('active{4}');
$repo->deleted->range('0{4}');
$repo->gen(4);

$repoUser = zenData('ops_repouser');
$repoUser->repo->range('1-4');
$repoUser->account->range('admin{4}');
$repoUser->gen(4);

$branchTable = zenData('ops_repobranch');
$branchTable->repo->range('2');
$branchTable->revision->range('1');
$branchTable->branch->range('main');
$branchTable->gen(1);

$entry = zenData('entry');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->gen(1);

class repoUpdateCommitHttpClient
{
    public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
    {
        return json_encode(array('code' => 'success', 'data' => array('id' => 1, 'path' => 'space/repo', 'gitURL' => 'http://gitfox.test/space/repo.git', 'gitSSHURL' => 'ssh://git@gitfox.test/space/repo.git', 'importing' => false)));
    }
}

$repoTest = new repoModelTest();
$oldHttpClient = common::$httpClient;
common::$httpClient = new repoUpdateCommitHttpClient();

r($repoTest->updateCommitTest(1)) && p('scmType,status') && e('git,exception');
r($repoTest->updateCommitTest(3)) && p('scmType,status') && e('svn,exception');
r($repoTest->updateCommitTest(4)) && p('scmType,status') && e('other,success');
r($repoTest->updateCommitTest(999)) && p('repoID,status') && e('999,repoNotFound');
r($repoTest->updateCommitTest(0)) && p('repoID,status') && e('0,repoNotFound');
$_COOKIE['repoBranch'] = 'main';
$repoTest->instance->cookie->repoBranch = 'main';
r($repoTest->updateCommitTest(2, 0, 'main')) && p('scmType,branchID,status') && e('git,main,exception');
r($repoTest->updateCommitTest(2, 123)) && p('scmType,objectID,status') && e('git,123,exception');

common::$httpClient = $oldHttpClient;
