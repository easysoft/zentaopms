#!/usr/bin/env php
<?php

/**

title=测试 docZen::checkPrivForEditLib();
timeout=0

- 开放自定义空间允许编辑 @1
- 私有库创建者允许编辑 @1
- 私有库白名单用户允许编辑 @1
- 无关用户不能编辑私有库 @0
- 软删除库不能编辑 @0
- main 库允许按对象权限编辑 @1
- 空间和库种类错配不能编辑 @0
- 自定义空间内的库允许编辑 @1
- 自己的我的空间允许编辑 @1
- 他人的我的空间不能编辑 @0
- 不允许通过通用路径编辑 API 库 @0

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

$openSpace         = $makeLib(103101, 'custom', 0, 'open', 'admin');
$privateOwnedLib   = $makeLib(103102, 'custom', 103101, 'private', 'user1');
$privateUserLib    = $makeLib(103103, 'custom', 103101, 'private', 'admin', 'user1');
$privateForeignLib = $makeLib(103104, 'custom', 103101, 'private', 'admin', 'user2');
$deletedLib        = $makeLib(103105, 'custom', 103101, 'open', 'admin');
$deletedLib->deleted = 1;
$mainLib           = $makeLib(103106, 'custom', 0, 'open', 'admin');
$mainLib->main     = 1;
$customSpaceLib    = $makeLib(103107, 'custom', 103101, 'open', 'admin');
$ownMineSpace      = $makeLib(103108, 'mine', 0, 'private', 'user1');
$foreignMineSpace  = $makeLib(103109, 'mine', 0, 'private', 'admin');
$apiLib            = $makeLib(103110, 'api', 103101, 'open', 'admin');

r($docTest->checkPrivForEditLibTest($openSpace, true)) && p() && e('1');
r($docTest->checkPrivForEditLibTest($privateOwnedLib, false)) && p() && e('1');
r($docTest->checkPrivForEditLibTest($privateUserLib, false)) && p() && e('1');
r($docTest->checkPrivForEditLibTest($privateForeignLib, false)) && p() && e('0');
r($docTest->checkPrivForEditLibTest($deletedLib, false)) && p() && e('0');
r($docTest->checkPrivForEditLibTest($mainLib, true)) && p() && e('1');
r($docTest->checkPrivForEditLibTest($customSpaceLib, true)) && p() && e('0');
r($docTest->checkPrivForEditLibTest($customSpaceLib, false)) && p() && e('1');
r($docTest->checkPrivForEditLibTest($ownMineSpace, true)) && p() && e('1');
r($docTest->checkPrivForEditLibTest($foreignMineSpace, true)) && p() && e('0');
r($docTest->checkPrivForEditLibTest($apiLib, false)) && p() && e('0');
