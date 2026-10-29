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
- 自己的我的空间允许移动文档 @1
- 他人的我的空间不能作为移动目标 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $tester;
$tester->app->user->account = 'user1';
$tester->app->user->admin   = false;

$docTest = new docZenTest();

$makeLib = function(int $id, string $type, string $acl, string $addedBy, string $users = ''): object
{
    $lib = new stdClass();
    $lib->id      = $id;
    $lib->type    = $type;
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

$openLib          = $makeLib(101, 'custom', 'open', 'admin');
$privateOwnedLib  = $makeLib(102, 'custom', 'private', 'user1');
$privateUserLib   = $makeLib(103, 'custom', 'private', 'admin', 'user1');
$privateForeignLib = $makeLib(104, 'custom', 'private', 'admin');
$deletedLib       = $makeLib(105, 'custom', 'open', 'admin');
$deletedLib->deleted = 1;
$ownMineSpace     = $makeLib(106, 'mine', 'private', 'user1');
$foreignMineSpace = $makeLib(107, 'mine', 'private', 'admin');

r($docTest->checkPrivForMoveDocTest($openLib)) && p() && e('1');
r($docTest->checkPrivForMoveDocTest($privateOwnedLib)) && p() && e('1');
r($docTest->checkPrivForMoveDocTest($privateUserLib)) && p() && e('1');
r($docTest->checkPrivForMoveDocTest($privateForeignLib)) && p() && e('0');
r($docTest->checkPrivForMoveDocTest($deletedLib)) && p() && e('0');
r($docTest->checkPrivForMoveDocTest($ownMineSpace)) && p() && e('1');
r($docTest->checkPrivForMoveDocTest($foreignMineSpace)) && p() && e('0');
