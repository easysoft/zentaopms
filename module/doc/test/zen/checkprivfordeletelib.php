#!/usr/bin/env php
<?php

/**

title=测试 docZen::checkPrivForDeleteLib();
timeout=0

- 开放自定义空间允许删除 @1
- 私有库创建者允许删除 @1
- 私有库白名单用户允许删除 @1
- 无关用户不能删除私有库 @0
- 软删除库不能删除 @0
- main 库不能删除 @0
- 空间和库种类错配不能删除 @0
- 自定义空间内的库允许删除 @1
- 自己的我的空间允许删除 @1
- 他人的我的空间不能删除 @0
- 不允许通过通用路径删除 API 库 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $tester;
$tester->app->user->account = 'user1';
$tester->app->user->admin   = false;
$tester->app->user->groups  = array(1, 2);

$docTest = new docZenTest();

$makeLib = function(int $id, string $type, int $parent, string $acl, string $addedBy, string $users = ''): object
{
    $lib = new stdClass();
    $lib->id        = $id;
    $lib->type      = $type;
    $lib->parent    = $parent;
    $lib->product   = 0;
    $lib->project   = 0;
    $lib->execution = 0;
    $lib->acl       = $acl;
    $lib->addedBy   = $addedBy;
    $lib->users     = $users;
    $lib->groups    = '';
    $lib->deleted   = 0;
    $lib->main      = 0;
    return $lib;
};

$openSpace         = $makeLib(101, 'custom', 0, 'open', 'admin');
$privateOwnedLib   = $makeLib(102, 'custom', 101, 'private', 'user1');
$privateUserLib    = $makeLib(103, 'custom', 101, 'private', 'admin', 'user1');
$privateForeignLib = $makeLib(104, 'custom', 101, 'private', 'admin', 'user2');
$deletedLib        = $makeLib(105, 'custom', 101, 'open', 'admin');
$deletedLib->deleted = 1;
$mainLib           = $makeLib(106, 'custom', 0, 'open', 'admin');
$mainLib->main     = 1;
$customSpaceLib    = $makeLib(107, 'custom', 101, 'open', 'admin');
$ownMineSpace      = $makeLib(108, 'mine', 0, 'private', 'user1');
$foreignMineSpace  = $makeLib(109, 'mine', 0, 'private', 'admin');
$apiLib            = $makeLib(110, 'api', 101, 'open', 'admin');

r($docTest->checkPrivForDeleteLibTest($openSpace, true)) && p() && e('1');
r($docTest->checkPrivForDeleteLibTest($privateOwnedLib, false)) && p() && e('1');
r($docTest->checkPrivForDeleteLibTest($privateUserLib, false)) && p() && e('1');
r($docTest->checkPrivForDeleteLibTest($privateForeignLib, false)) && p() && e('0');
r($docTest->checkPrivForDeleteLibTest($deletedLib, false)) && p() && e('0');
r($docTest->checkPrivForDeleteLibTest($mainLib, true)) && p() && e('0');
r($docTest->checkPrivForDeleteLibTest($customSpaceLib, true)) && p() && e('0');
r($docTest->checkPrivForDeleteLibTest($customSpaceLib, false)) && p() && e('1');
r($docTest->checkPrivForDeleteLibTest($ownMineSpace, true)) && p() && e('1');
r($docTest->checkPrivForDeleteLibTest($foreignMineSpace, true)) && p() && e('0');
r($docTest->checkPrivForDeleteLibTest($apiLib, false)) && p() && e('0');
