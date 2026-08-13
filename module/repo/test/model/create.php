#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

class repoCreateHttpClient
{
    public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
    {
        if(strpos($url, '/webhooks') !== false)
        {
            if(strtoupper($method) == 'GET') return json_encode((object)array('code' => 'success', 'data' => array()));
            return json_encode((object)array('code' => 'success', 'data' => (object)array('id' => 1)));
        }

        return json_encode((object)array('code' => 'success', 'data' => (object)array('gitURL' => 'http://localhost:3000/repo.git', 'path' => 'repo', 'importing' => false)));
    }
}

/**

title=测试 repoModel->create();
timeout=0
cid=18035

- 正常创建 Gitlab 版本库属性id @1
- 重复 Gitlab 名称创建第name条的0属性 @『名称』已经有『zzxx』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 客户端为空创建 Gitea 版本库第client条的0属性 @『客户端』不能为空。
- 正常创建 Gitea 版本库属性scmType @git
- 客户端为空创建 Git 版本库第client条的0属性 @『客户端』不能为空。
- 客户端为空创建 SVN 版本库第client条的0属性 @『客户端』不能为空。

*/

zenData('ops_repo')->gen(0);
zenData('ops_repouser')->gen(0);

$entry = zenData('entry');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->gen(1);

$_SERVER['REQUEST_URI'] = 'http://unittest.com';

su('admin');

$repo = new repoModelTest();
$oldHttpClient = common::$httpClient;
common::$httpClient = new repoCreateHttpClient();

$gitlab = array(
    'space'          => 1,
    'product'        => '1',
    'SCM'            => 'Gitlab',
    'acl'            => 'private',
    'serviceHost'    => 1,
    'serviceProject' => 100,
    'name'           => 'zzxx',
    'path'           => '/var/www/html/zentaopms/www/data/repo/zzxx',
    'encoding'       => 'utf-8',
    'client'         => '',
    'desc'           => '',
    'gitUID'         => 'create-gitlab-gituid-1',
);

$gitea = array(
    'space'          => 1,
    'product'        => '1',
    'SCM'            => 'Gitea',
    'acl'            => 'private',
    'serviceHost'    => 4,
    'serviceProject' => 'gitea/unittest',
    'name'           => 'Demo',
    'path'           => '/var/www/html/zentaopms/www/data/repo/Demo',
    'encoding'       => 'utf-8',
    'client'         => '',
    'desc'           => '',
    'uid'            => '6322b184f3a72',
    'gitUID'         => 'create-gitea-gituid-1',
);

$git = array(
    'space'          => 1,
    'product'        => '1',
    'SCM'            => 'Git',
    'acl'            => 'private',
    'name'           => '本地git',
    'path'           => '/var/www/html/zentaopms/',
    'encoding'       => 'utf-8',
    'client'         => '',
    'desc'           => '',
    'gitUID'         => 'create-git-gituid-1',
);

$svn = array(
    'space'          => 1,
    'product'        => '1',
    'SCM'            => 'Subversion',
    'acl'            => 'private',
    'name'           => 'svn',
    'path'           => 'https://svn.zcorp.cc',
    'encoding'       => 'utf-8',
    'account'        => 'user1',
    'password'       => base64_encode('123456'),
    'encrypt'        => 'base64',
    'client'         => '',
    'desc'           => '',
    'gitUID'         => 'create-svn-gituid-1',
);

r($repo->createTest($gitlab))            && p('id')     && e('1');
r($repo->createTest($gitlab))            && p('name:0') && e('『名称』已经有『zzxx』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');
r($repo->createTest($gitea))        && p('client:0')    && e('『客户端』不能为空。');
$gitea['client'] = '/usr/bin/git';
r($repo->createTest($gitea))             && p('scmType') && e('git');
r($repo->createTest($git, false))        && p('client:0') && e('『客户端』不能为空。');
r($repo->createTest($svn, false))        && p('client:0') && e('『客户端』不能为空。');

common::$httpClient = $oldHttpClient;
