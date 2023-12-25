#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';

/**

title=测试 repoModel->create();
timeout=0
cid=1

- 正常创建gitlab版本库属性id @1
- 当已有版本库时提示已有记录第name条的0属性 @『名称』已经有『zzxx』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 客户端为空创建gitea版本库第client条的0属性 @『客户端』不能为空。
- 正常创建gitea版本库属性SCM @Gitea
- 客户端为空创建git版本库第client条的0属性 @『客户端』不能为空。
- 正常创建git版本库属性SCM @Git
- 客户端为空创建svn版本库第client条的0属性 @『客户端』不能为空。
- 正常创建svn版本库属性SCM @Subversion

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->gen(0);
$_SERVER['REQUEST_URI'] = 'http://unittest.com';

$gitlab = array(
    'product'        => '1',
    'SCM'            => 'Gitlab',
    'serviceHost'    => '1',
    'serviceProject' => '2',
    'name'           => 'zzxx',
    'encoding'       => 'utf-8',
    'encrypt'        => 'base64',
    'desc'           => '',
    'uid'            => '6321819c78be5'
);

$gitea = array(
    'SCM'            => 'Gitea',
    'serviceHost'    => 4,
    'serviceProject' => 'gitea/unittest',
    'name'           => 'Demo',
    'path'           => '/var/www/html/zentaopms/www/data/repo/Demo',
    'encoding'       => 'utf-8',
    'client'         => '',
    'desc'           => '',
    'uid'            => '6322b184f3a72'
);

$git = array(
    'SCM'            => 'Git',
    'name'           => '本地git',
    'path'           => '/var/www/html/zentaopms/',
    'encoding'       => 'utf-8',
    'client'         => '',
    'desc'           => '',
);

$svn = array(
    'product'        => '1',
    'SCM'            => 'Subversion',
    'name'           => 'svn',
    'path'           => 'https://svn.zcorp.cc',
    'encoding'       => 'utf-8',
    'account'        => 'user1',
    'password'       => base64_encode('123456'),
    'encrypt'        => 'base64',
    'client'         => '',
    'desc'           => '',
);

$repo = new repoTest();
r($repo->createTest($gitlab))      && p('id')     && e('1');                                                                                         //正常创建gitlab版本库
r($repo->createTest($gitlab))      && p('name:0') && e('『名称』已经有『zzxx』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); //当已有版本库时提示已有记录
r($repo->createTest($gitea))       && p('client:0')    && e('『客户端』不能为空。');                                                                 //客户端为空创建gitea版本库
$gitea['client'] = '/usr/bin/git';
r($repo->createTest($gitea))       && p('SCM')    && e('Gitea');                                                                                     //正常创建gitea版本库
r($repo->createTest($git, false))  && p('client:0')    && e('『客户端』不能为空。');                                                                 //客户端为空创建git版本库
$git['client'] = '/usr/bin/git';
r($repo->createTest($git, false))  && p('SCM')    && e('Git');                                                                                       //正常创建git版本库
r($repo->createTest($svn, false))  && p('client:0')    && e('『客户端』不能为空。');                                                                 //客户端为空创建svn版本库
$svn['client'] = '/usr/bin/git';
r($repo->createTest($svn, false))  && p('SCM')    && e('Subversion');                                                                                //正常创建svn版本库