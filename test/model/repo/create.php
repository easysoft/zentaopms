#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/repo.class.php';
su('admin');

/**

title=测试 repoModel->create();
cid=1
pid=1

*/

$repo = new repoTest();

$list = array(
    'product'        => '1',
    'SCM'            => 'Gitlab',
    'serviceHost'    => '2',
    'serviceProject' => '971',
    'name'           => 'zzxx',
    'path'           => '',
    'encoding'       => 'utf-8',
    'client'         => '/usr/bin/git',
    'account'        => '',
    'password'       => '',
    'encrypt'        => 'base64',
    'desc'           => '',
    'uid'            => '6321819c78be5'
);

$listOne = array(
    'SCM'            => 'Gitea',
    'serviceHost'    => 2,
    'serviceProject' => 'root/Demo',
    'name'           => 'Demo',
    'path'           => '',
    'encoding'       => 'utf-8',
    'client'         => '/usr/bin/git',
    'account'        => '',
    'password'       => '',
    'encrypt'        => 'base64',
    'desc'           => '',
    'uid'            => '6322b184f3a72'
);

$resultOne   = $repo->createTest($list);
$resultTwo   = $repo->createTest($list);
$resultThree = $repo->createTest($listOne);

r($resultOne)   && p($resultOne)   && e('1');
r($resultTwo)   && p('name:0')     && e('『名称』已经有『jktest』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。');
r($resultThree) && p($resultThree) && e('因为安全原因，需要检测客户端版本，请将版本号写入文件');
