#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getByIdList();
timeout=0
cid=16026

- 通过真实空间ID列表获取数量 @2
- 通过空间ID列表获取第1个空间名称匹配动态创建值 @1
- 通过空间ID列表获取第2个空间访问控制 @open
- 通过无效空间ID列表确认找不到目标空间 @1
- showDeleted=false时仍可获取有效空间 @2

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

$spaceA = new stdClass();
$spaceA->name        = "ut-getbyidlist-space-a-{$suffix}";
$spaceA->code        = "utgetbyidlista{$suffix}";
$spaceA->desc        = 'getByIdList private space';
$spaceA->acl         = 'private';
$spaceA->auth        = 'extend';
$spaceA->createdBy   = 'admin';
$spaceA->createdDate = '2026-07-29 10:16:00';

$spaceB = new stdClass();
$spaceB->name        = "ut-getbyidlist-space-b-{$suffix}";
$spaceB->code        = "utgetbyidlistb{$suffix}";
$spaceB->desc        = 'getByIdList open space';
$spaceB->acl         = 'open';
$spaceB->auth        = 'extend';
$spaceB->createdBy   = 'admin';
$spaceB->createdDate = '2026-07-29 10:16:00';

$spaceID1 = (int)$spaceTester->createTest($spaceA);
$spaceID2 = (int)$spaceTester->createTest($spaceB);
$spaceIDs = array($spaceID1, $spaceID2);

r($spaceTester->getByIdListCountTest($spaceIDs, true))                      && p() && e('2');                                   // 通过真实空间ID列表获取数量
r($spaceTester->getByIdListSpaceFieldEqualsTest($spaceIDs, true, $spaceID1, 'name', "ut-getbyidlist-space-a-{$suffix}")) && p() && e('1'); // 通过空间ID列表获取第1个空间名称匹配动态创建值
r($spaceTester->getByIdListSpaceFieldTest($spaceIDs, true, $spaceID2, 'acl'))  && p() && e('open');                               // 通过空间ID列表获取第2个空间访问控制
r($spaceTester->getByIdListSpaceFieldEqualsTest(array(999999), true, 999999, 'name', '')) && p() && e('1');                // 通过无效空间ID列表确认找不到目标空间
r($spaceTester->getByIdListCountTest($spaceIDs, false))                    && p() && e('2');                                   // showDeleted=false时仍可获取有效空间
