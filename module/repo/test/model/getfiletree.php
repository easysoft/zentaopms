#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getFileTree();
timeout=0
cid=18057

- 获取代码文件得提交信息第一个文件
 - 第0条的parent属性 @0
 - 第0条的name属性 @LICENSE
 - 第0条的path属性 @LICENSE
- 获取代码文件得提交信息数量大于1 @1
- 获取svn代码文件得提交信息第一个文件夹信息
 - 第0条的id属性 @dGFn
 - 第0条的name属性 @tag
 - 第0条的parent属性 @0
- 获取svn代码文件得提交信息第一个文件夹信息
 - 第0条的id属性 @dGFnJTJGUkVBRE1FLm1k
 - 第0条的name属性 @README.md
 - 第0条的parent属性 @dGFn
- 获取svn代码文件得提交信息数量 @1

*/

$repoData = zenData('ops_repo');
$repoData->id->range('3-4');
$repoData->spaceID->range('1{2}');
$repoData->product->range('1{2}');
$repoData->name->range('giteaRepo,svnRepo');
$repoData->scmType->range('git,svn');
$repoData->gitUID->range('filetree-gituid-3,filetree-gituid-4');
$repoData->acl->range('private{2}');
$repoData->status->range('active{2}');
$repoData->deleted->range('0{2}');
$repoData->gen(2);

$repoUser = zenData('ops_repouser');
$repoUser->repo->range('3-4');
$repoUser->account->range('admin{2}');
$repoUser->gen(2);

$history = zenData('ops_repohistory');
$history->id->range('1-2');
$history->repo->range('3-4');
$history->revision->range('git-tree,1');
$history->commit->range('1-2');
$history->comment->range('Add files,+ Add file.');
$history->committer->range('admin{2}');
$history->time->range('13,14')->prefix('2023-12-')->postfix(' 19:00:25');
$history->gen(2);

$file = zenData('ops_repofiles');
$file->repo->range('3,3,4');
$file->revision->range('1,1,2');
$file->path->range('/LICENSE,/README.md,/tag/README.md');
$file->oldPath->range('[]{3}');
$file->parent->range('/,/,/tag');
$file->type->range('file{3}');
$file->action->range('A{3}');
$file->gen(3);

$entry = zenData('entry');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->gen(1);

class repoGetFileTreeHttpClient
{
    public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
    {
        return json_encode(array('code' => 'success', 'data' => array('id' => 1, 'path' => 'space/repo', 'gitURL' => 'http://gitfox.test/space/repo.git', 'gitSSHURL' => 'ssh://git@gitfox.test/space/repo.git', 'importing' => false)));
    }
}

$repo = new repoModelTest();
$oldHttpClient = common::$httpClient;
common::$httpClient = new repoGetFileTreeHttpClient();
r($repo->getFileTreeTest(3, '')) && p('0:parent,name,path') && e('0,LICENSE,LICENSE');
r($repo->getFileTreeCountGreaterThanTest(3, '', 1)) && p() && e('1');

r($repo->getFileTreeTest(4, '')) && p('0:id,name,parent') && e('dGFn,tag,0');
r($repo->getFileTreeChildrenTest(4, '', 0)) && p('0:id,name,parent') && e('dGFnJTJGUkVBRE1FLm1k,README.md,dGFn');
r($repo->getFileTreeCountTest(4, '')) && p() && e('1');

common::$httpClient = $oldHttpClient;
