#!/usr/bin/env php
<?php
/**
title=测试 spaceModel::update();
timeout=0
cid=16032

- 更新真实空间名称的变更值匹配动态创建值 @1
- 更新后再次查询真实空间名称匹配动态创建值 @1
- 更新真实空间描述的变更值 @updated desc C
- 更新后再次查询真实空间访问控制 @open
- 更新本地管理员列表数量 @2
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(0);
zenData('ops_spaceuser')->gen(0);
zenData('entry')->loadYaml('entry')->gen(2);

su('admin');

$spaceTester = new spaceModelTest();
$suffix      = date('YmdHis') . mt_rand(1000, 9999);

$createSpace = function(string $name, string $code, string $acl = 'private') use ($spaceTester): int
{
    $space = new stdClass();
    $space->name        = $name;
    $space->code        = $code;
    $space->desc        = "desc for {$name}";
    $space->acl         = $acl;
    $space->auth        = 'extend';
    $space->createdBy   = 'admin';
    $space->createdDate = '2026-07-29 10:21:00';
    return (int)$spaceTester->createTest($space);
};

$spaceID1 = $createSpace("ut-update-space-a-{$suffix}", "utupdatespacea{$suffix}");
$spaceID2 = $createSpace("ut-update-space-b-{$suffix}", "utupdatespaceb{$suffix}");
$spaceID3 = $createSpace("ut-update-space-c-{$suffix}", "utupdatespacec{$suffix}");
$spaceID4 = $createSpace("ut-update-space-d-{$suffix}", "utupdatespaced{$suffix}");
$spaceID5 = $createSpace("ut-update-space-e-{$suffix}", "utupdatespacee{$suffix}");

$oldSpace1 = $spaceTester->getByIDTest($spaceID1);
$oldSpace2 = $spaceTester->getByIDTest($spaceID2);
$oldSpace3 = $spaceTester->getByIDTest($spaceID3);
$oldSpace4 = $spaceTester->getByIDTest($spaceID4);
$oldSpace5 = $spaceTester->getByIDTest($spaceID5);

$formA = new stdClass();
$formA->name       = "ut-update-space-a-new-{$suffix}";
$formA->code       = "utupdatespacea{$suffix}";
$formA->desc       = "desc for ut-update-space-a-{$suffix}";
$formA->acl        = 'private';
$formA->auth       = 'extend';
$formA->manager    = 'admin';
$formA->editedBy   = 'admin';
$formA->editedDate = '2026-07-29 10:21:30';

$formB = new stdClass();
$formB->name       = "ut-update-space-b-new-{$suffix}";
$formB->code       = "utupdatespaceb{$suffix}";
$formB->desc       = "desc for ut-update-space-b-{$suffix}";
$formB->acl        = 'private';
$formB->auth       = 'extend';
$formB->manager    = 'admin';
$formB->editedBy   = 'admin';
$formB->editedDate = '2026-07-29 10:21:30';

$formC = new stdClass();
$formC->name       = "ut-update-space-c-{$suffix}";
$formC->code       = "utupdatespacec{$suffix}";
$formC->desc       = 'updated desc C';
$formC->acl        = 'private';
$formC->auth       = 'extend';
$formC->manager    = 'admin';
$formC->editedBy   = 'admin';
$formC->editedDate = '2026-07-29 10:21:30';

$formD = new stdClass();
$formD->name       = "ut-update-space-d-{$suffix}";
$formD->code       = "utupdatespaced{$suffix}";
$formD->desc       = "desc for ut-update-space-d-{$suffix}";
$formD->acl        = 'open';
$formD->auth       = 'extend';
$formD->manager    = 'admin';
$formD->editedBy   = 'admin';
$formD->editedDate = '2026-07-29 10:21:30';

$formE = new stdClass();
$formE->name       = "ut-update-space-e-{$suffix}";
$formE->code       = "utupdatespacee{$suffix}";
$formE->desc       = "desc for ut-update-space-e-{$suffix}";
$formE->acl        = 'private';
$formE->auth       = 'extend';
$formE->manager    = 'admin,test1';
$formE->editedBy   = 'admin';
$formE->editedDate = '2026-07-29 10:21:30';

r($spaceTester->updateChangeFieldEqualsTest($oldSpace1, $formA, 'name', 'new', "ut-update-space-a-new-{$suffix}")) && p() && e('1'); // 更新真实空间名称的变更值匹配动态创建值
r($spaceTester->updateAndGetFieldEqualsTest($oldSpace2, $formB, 'name', "ut-update-space-b-new-{$suffix}"))         && p() && e('1'); // 更新后再次查询真实空间名称匹配动态创建值
r($spaceTester->updateChangeFieldTest($oldSpace3, $formC, 'desc', 'new'))  && p() && e('updated desc C');                  // 更新真实空间描述的变更值
r($spaceTester->updateAndGetFieldTest($oldSpace4, $formD, 'acl'))          && p() && e('open');                            // 更新后再次查询真实空间访问控制
r($spaceTester->updateAndGetManagerCountTest($oldSpace5, $formE))          && p() && e('2');                               // 更新本地管理员列表数量
