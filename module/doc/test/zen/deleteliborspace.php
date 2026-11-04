#!/usr/bin/env php
<?php

/**

title=测试 docZen::deleteLibOrSpace();
timeout=0

- 删除有权私有库返回成功 @success
- 删除有权私有库后标记 deleted @1
- 删除有权私有库生成删除动作 @1
- 删除无权私有库返回失败 @fail
- 删除无权私有库不改变 deleted @0
- 删除 main 库返回失败 @fail
- 删除空间/库种类错配返回失败 @fail
- 删除软删除库返回失败 @fail
- 删除不存在的库返回失败 @fail
- 删除有权空间返回成功 @success
- 删除有权空间后标记 deleted @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $tester;
$tester->app->user->account = 'user1';
$tester->app->user->admin   = false;
$tester->app->user->groups  = array(1, 2);

$ids = array(902201, 902202, 902203, 902204, 902205, 902206, 902207);
$tester->dao->delete()->from(TABLE_ACTION)->where('objectType')->in(array('doclib', 'docspace'))->andWhere('objectID')->in($ids)->exec();
$tester->dao->delete()->from(TABLE_DOCLIB)->where('id')->in($ids)->exec();

$makeLib = function(int $id, string $type, int $parent, string $acl, string $addedBy, int $main = 0, int $deleted = 0) use ($tester): void
{
    $lib = new stdClass();
    $lib->id        = $id;
    $lib->type      = $type;
    $lib->parent    = $parent;
    $lib->product   = 0;
    $lib->project   = 0;
    $lib->execution = 0;
    $lib->name      = "unit-vuln022-{$id}";
    $lib->acl       = $acl;
    $lib->groups    = '';
    $lib->users     = '';
    $lib->main      = $main;
    $lib->addedBy   = $addedBy;
    $lib->addedDate = helper::now();
    $lib->vision    = 'rnd';
    $lib->deleted   = $deleted;
    $tester->dao->insert(TABLE_DOCLIB)->data($lib)->exec();
};

$makeLib(902201, 'custom', 0, 'open', 'user1');
$makeLib(902202, 'custom', 902201, 'open', 'user1');
$makeLib(902203, 'custom', 902201, 'private', 'admin');
$makeLib(902204, 'custom', 0, 'open', 'user1', 1);
$makeLib(902205, 'custom', 902201, 'open', 'user1');
$makeLib(902206, 'custom', 902201, 'open', 'user1', 0, 1);

$docTest = new docZenTest();

r($docTest->deleteLibOrSpaceTest(902202, false)) && p('result') && e('success');
r($tester->dao->select('deleted')->from(TABLE_DOCLIB)->where('id')->eq(902202)->fetch('deleted')) && p() && e('1');
r($tester->dao->select('count(1) as count')->from(TABLE_ACTION)->where('objectType')->eq('doclib')->andWhere('objectID')->eq(902202)->andWhere('action')->eq('deleted')->fetch('count')) && p() && e('1');

r($docTest->deleteLibOrSpaceTest(902203, false)) && p('result') && e('fail');
r($tester->dao->select('deleted')->from(TABLE_DOCLIB)->where('id')->eq(902203)->fetch('deleted')) && p() && e('0');
r($docTest->deleteLibOrSpaceTest(902204, false)) && p('result') && e('fail');
r($docTest->deleteLibOrSpaceTest(902205, true)) && p('result') && e('fail');
r($docTest->deleteLibOrSpaceTest(902206, false)) && p('result') && e('fail');
r($docTest->deleteLibOrSpaceTest(999999, false)) && p('result') && e('fail');

r($docTest->deleteLibOrSpaceTest(902201, true)) && p('result') && e('success');
r($tester->dao->select('deleted')->from(TABLE_DOCLIB)->where('id')->eq(902201)->fetch('deleted')) && p() && e('1');

$tester->dao->delete()->from(TABLE_ACTION)->where('objectType')->in(array('doclib', 'docspace'))->andWhere('objectID')->in($ids)->exec();
$tester->dao->delete()->from(TABLE_DOCLIB)->where('id')->in($ids)->exec();
