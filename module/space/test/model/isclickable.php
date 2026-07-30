#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::isClickable();
timeout=0
cid=0

- 经理不能移除成员 @0
- 普通成员可以移除成员 @1
- 动作大小写不敏感 @1
- 经理查看动作始终可点击 @1
- 空角色执行普通动作可点击 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$spaceTester = new spaceModelTest();

$manager = new stdclass();
$manager->role = 'manager';

$member = new stdclass();
$member->role = 'member';

$guest = new stdclass();
$guest->role = '';

r($spaceTester->isClickableTest($manager, 'removeMember')) && p() && e('0');   // 经理不能移除成员
r($spaceTester->isClickableTest($member, 'removeMember')) && p() && e('1');    // 普通成员可以移除成员
r($spaceTester->isClickableTest($member, 'REMOVEMEMBER')) && p() && e('1');    // 动作大小写不敏感
r($spaceTester->isClickableTest($manager, 'view')) && p() && e('1');           // 经理查看动作始终可点击
r($spaceTester->isClickableTest($guest, 'edit')) && p() && e('1');             // 空角色执行普通动作可点击
