#!/usr/bin/env php
<?php

/**

title=测试 docZen::editLibOrSpace();
timeout=0

- 无权限编辑私有库返回失败 @fail
- 无权限编辑不改变库名称 @0
- 方法种类错配返回失败 @fail
- GET 编辑有权库返回空数组 @0
- 编辑有权库返回成功 @success
- 编辑有权库后更新名称 @1
- 编辑有权库生成编辑动作 @1
- 编辑入口不能移动库 @fail
- 编辑入口不能改变库所属空间 @1
- 编辑有权空间返回成功 @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $tester;
$tester->app->user->account = 'user1';
$tester->app->user->admin   = false;
$tester->app->user->groups  = array(1, 2);

$ids = array(903301, 903302, 903303, 903304);
$tester->dao->delete()->from(TABLE_ACTION)->where('objectType')->in(array('doclib', 'docspace'))->andWhere('objectID')->in($ids)->exec();
$tester->dao->delete()->from(TABLE_DOCLIB)->where('id')->in($ids)->exec();

$makeLib = function(int $id, string $type, int $parent, string $name, string $acl, string $addedBy) use ($tester): void
{
    $lib = new stdClass();
    $lib->id        = $id;
    $lib->type      = $type;
    $lib->parent    = $parent;
    $lib->product   = 0;
    $lib->project   = 0;
    $lib->execution = 0;
    $lib->name      = $name;
    $lib->acl       = $acl;
    $lib->groups    = '';
    $lib->users     = '';
    $lib->main      = 0;
    $lib->addedBy   = $addedBy;
    $lib->addedDate = helper::now();
    $lib->vision    = 'rnd';
    $lib->deleted   = 0;
    $tester->dao->insert(TABLE_DOCLIB)->data($lib)->exec();
};

$makeLib(903301, 'custom', 0, 'unit-vuln023-space', 'open', 'user1');
$makeLib(903302, 'custom', 903301, 'unit-vuln023-lib', 'open', 'user1');
$makeLib(903303, 'custom', 903301, 'unit-vuln023-private', 'private', 'admin');
$makeLib(903304, 'custom', 0, 'unit-vuln023-other-space', 'open', 'user1');

$docTest = new docZenTest();
$_POST = array
(
    'space'  => 'custom.903301',
    'name'   => 'unit-vuln023-private-changed',
    'acl'    => 'open',
    'groups' => array(),
    'users'  => array()
);
r($docTest->editLibOrSpaceTest(903303, false)) && p('result') && e('fail');
$_POST = array();
r($tester->dao->select('name')->from(TABLE_DOCLIB)->where('id')->eq(903303)->fetch('name') === 'unit-vuln023-private' ? 0 : 1) && p() && e('0');
r($docTest->editLibOrSpaceTest(903302, true)) && p('result') && e('fail');
r(count($docTest->editLibOrSpaceTest(903302, false))) && p() && e('0');

$_POST = array
(
    'space'  => 'custom.903301',
    'name'   => 'unit-vuln023-lib-edited',
    'acl'    => 'open',
    'groups' => array(),
    'users'  => array()
);
r($docTest->editLibOrSpaceTest(903302, false)) && p('result') && e('success');
r($tester->dao->select('name')->from(TABLE_DOCLIB)->where('id')->eq(903302)->fetch('name') === 'unit-vuln023-lib-edited' ? 1 : 0) && p() && e('1');
r($tester->dao->select('count(1) as count')->from(TABLE_ACTION)->where('objectType')->eq('doclib')->andWhere('objectID')->eq(903302)->andWhere('action')->eq('edited')->fetch('count')) && p() && e('1');

$_POST = array
(
    'space'  => 'custom.903304',
    'name'   => 'unit-vuln023-lib-moved',
    'acl'    => 'open',
    'groups' => array(),
    'users'  => array()
);
r($docTest->editLibOrSpaceTest(903302, false)) && p('result') && e('fail');
r($tester->dao->select('parent')->from(TABLE_DOCLIB)->where('id')->eq(903302)->fetch('parent')) && p() && e('903301');

$_POST = array
(
    'space'  => 'custom.0',
    'name'   => 'unit-vuln023-space-edited',
    'acl'    => 'open',
    'groups' => array(),
    'users'  => array()
);
r($docTest->editLibOrSpaceTest(903301, true)) && p('result') && e('success');

$_POST = array();
$tester->dao->delete()->from(TABLE_ACTION)->where('objectType')->in(array('doclib', 'docspace'))->andWhere('objectID')->in($ids)->exec();
$tester->dao->delete()->from(TABLE_DOCLIB)->where('id')->in($ids)->exec();
