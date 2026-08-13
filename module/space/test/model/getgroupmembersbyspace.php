#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getGroupMembersBySpace();
timeout=0
cid=0

- 查询所有空间的分组成员并验证结果类型 @1
- 查询有效空间ID=1的分组成员并验证结果类型 @1
- 查询无效空间ID=9999的分组成员为空 @0
- 查询空间ID=0的分组成员并验证结果类型 @1
- 查询空间ID=1并用allVision参数查询并验证结果类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

global $tester;
$vision      = $tester->config->vision;
$otherVision = $vision == 'rnd' ? 'or' : 'rnd';

$group = zenData('group');
$group->id->range('1-4');
$group->name->range('space1-group-a,space1-group-b,space2-group-a,space1-group-hidden');
$group->project->range('0{4}');
$group->devopsSpace->range('1,1,2,1');
$group->vision->range("{$vision},{$vision},{$vision},{$otherVision}");
$group->gen(4);

$userGroup = zenData('usergroup');
$userGroup->account->range('admin,test1,test2,test3');
$userGroup->group->range('1,2,3,4');
$userGroup->project->range('0{4}');
$userGroup->gen(4);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getGroupMembersBySpaceCountTest())        && p() && e('3');      // 查询所有空间的分组成员数量
r($spaceTester->getGroupMembersBySpaceCountTest(1))       && p() && e('2');      // 查询空间1下的分组成员数量
r($spaceTester->getGroupMembersBySpaceCountTest(9999))    && p() && e('0');      // 查询无效空间的分组成员为空
r($spaceTester->getGroupMembersBySpaceCountTest(0))       && p() && e('3');      // 查询所有非0空间的分组成员数量
r($spaceTester->getGroupMembersBySpaceCountTest(1, true)) && p() && e('3');      // 查询空间1在全视图下的分组成员数量
