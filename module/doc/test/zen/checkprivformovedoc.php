#!/usr/bin/env php
<?php

/**

title=测试 docZen::checkPrivForMoveDoc();
timeout=0

- 开放库允许移动文档 @1
- 私有库创建者允许移动文档 @1
- 私有库白名单用户允许移动文档 @1
- 无关用户不能把文档移入私有库 @0
- 软删除库不能被选作移动目标 @0
- 自己的我的空间下的库允许移动文档 @1
- 他人的我的空间下的库不能作为移动目标 @0
- API 库不能作为文档移动目标 @0
- 团队空间节点不能作为文档移动目标 @0
- 我的空间节点不能作为文档移动目标 @0
- 未知类型不能作为文档移动目标 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $tester;
$tester->app->user->account = 'user1';
$tester->app->user->admin   = false;

$docTest = new docZenTest();

$makeLib = function(int $id, string $type, int $parent, string $acl, string $addedBy, string $users = ''): object
{
    $lib = new stdClass();
    $lib->id      = $id;
    $lib->type    = $type;
    $lib->parent  = $parent;
    $lib->product = 0;
    $lib->project = 0;
    $lib->execution = 0;
    $lib->acl     = $acl;
    $lib->addedBy = $addedBy;
    $lib->users   = $users;
    $lib->groups  = '';
    $lib->deleted = 0;
    return $lib;
};

$openLib           = $makeLib(101, 'custom', 100, 'open', 'admin');
$privateOwnedLib   = $makeLib(102, 'custom', 100, 'private', 'user1');
$privateUserLib    = $makeLib(103, 'custom', 100, 'private', 'admin', 'user1');
$privateForeignLib = $makeLib(104, 'custom', 100, 'private', 'admin');
$deletedLib        = $makeLib(105, 'custom', 100, 'open', 'admin');
$deletedLib->deleted = 1;
$ownMineLib        = $makeLib(106, 'mine', 100, 'private', 'user1');
$ownMineSpace      = $makeLib(107, 'mine', 0, 'private', 'user1');
$foreignMineLib    = $makeLib(108, 'mine', 100, 'private', 'admin');
$apiLib            = $makeLib(109, 'api', 100, 'open', 'admin');
$customSpace       = $makeLib(110, 'custom', 0, 'open', 'admin');
$unknownLib        = $makeLib(111, 'unknown', 100, 'open', 'admin');

r($docTest->checkPrivForMoveDocTest($openLib)) && p() && e('1');
r($docTest->checkPrivForMoveDocTest($privateOwnedLib)) && p() && e('1');
r($docTest->checkPrivForMoveDocTest($privateUserLib)) && p() && e('1');
r($docTest->checkPrivForMoveDocTest($privateForeignLib)) && p() && e('0');
r($docTest->checkPrivForMoveDocTest($deletedLib)) && p() && e('0');
r($docTest->checkPrivForMoveDocTest($ownMineLib)) && p() && e('1');
r($docTest->checkPrivForMoveDocTest($ownMineSpace)) && p() && e('0');
r($docTest->checkPrivForMoveDocTest($foreignMineLib)) && p() && e('0');
r($docTest->checkPrivForMoveDocTest($apiLib)) && p() && e('0');
r($docTest->checkPrivForMoveDocTest($customSpace)) && p() && e('0');
r($docTest->checkPrivForMoveDocTest($unknownLib)) && p() && e('0');
