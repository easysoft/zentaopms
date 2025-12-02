#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('user')->gen(10);

/**

title=测试 commonModel::hasPriv();
timeout=0
cid=15680

- 查看admin是否有misc-changelog的权限 @1
- 查看admin是否有my-index的权限 @1
- 查看admin是否有company-browse的权限 @1
- 查看admin是否有product-browse的权限 @1
- 查看admin是否有product-requirement的权限 @1
- 查看admin是否有task-create的权限 @1
- 查看admin是否有story-view的权限 @1
- 查看admin是否有project-all的权限 @1
- 查看admin是否有execution-all的权限 @1
- 查看user1是否有misc-changelog的权限 @0
- 查看user1是否有my-index的权限 @1
- 查看user1是否有company-browse的权限 @0
- 查看user1是否有product-browse的权限 @0
- 查看user1是否有product-requirement的权限 @0
- 查看user1是否有task-create的权限 @0
- 查看user1是否有story-view的权限 @0
- 查看user1是否有project-all的权限 @0
- 查看user1是否有execution-all的权限 @0

*/

$result1 = commonModel::hasPriv('misc', 'changelog');
$result2 = commonModel::hasPriv('my', 'index');
$result3 = commonModel::hasPriv('company', 'browse');
$result4 = commonModel::hasPriv('product', 'browse');
$result5 = commonModel::hasPriv('product', 'requirement');
$result6 = commonModel::hasPriv('task', 'create');
$result7 = commonModel::hasPriv('story', 'view');
$result8 = commonModel::hasPriv('project', 'all');
$result9 = commonModel::hasPriv('execution', 'all');

r($result1) && p() && e('1'); // 查看admin是否有misc-changelog的权限
r($result2) && p() && e('1'); // 查看admin是否有my-index的权限
r($result3) && p() && e('1'); // 查看admin是否有company-browse的权限
r($result4) && p() && e('1'); // 查看admin是否有product-browse的权限
r($result5) && p() && e('1'); // 查看admin是否有product-requirement的权限
r($result6) && p() && e('1'); // 查看admin是否有task-create的权限
r($result7) && p() && e('1'); // 查看admin是否有story-view的权限
r($result8) && p() && e('1'); // 查看admin是否有project-all的权限
r($result9) && p() && e('1'); // 查看admin是否有execution-all的权限

su('user1');

$result1 = commonModel::hasPriv('misc', 'changelog');
$result2 = commonModel::hasPriv('my', 'index');
$result3 = commonModel::hasPriv('company', 'browse');
$result4 = commonModel::hasPriv('product', 'browse');
$result5 = commonModel::hasPriv('product', 'requirement');
$result6 = commonModel::hasPriv('task', 'create');
$result7 = commonModel::hasPriv('story', 'view');
$result8 = commonModel::hasPriv('project', 'all');
$result9 = commonModel::hasPriv('execution', 'all');

r($result1) && p() && e('0'); // 查看user1是否有misc-changelog的权限
r($result2) && p() && e('1'); // 查看user1是否有my-index的权限
r($result3) && p() && e('0'); // 查看user1是否有company-browse的权限
r($result4) && p() && e('0'); // 查看user1是否有product-browse的权限
r($result5) && p() && e('0'); // 查看user1是否有product-requirement的权限
r($result6) && p() && e('0'); // 查看user1是否有task-create的权限
r($result7) && p() && e('0'); // 查看user1是否有story-view的权限
r($result8) && p() && e('0'); // 查看user1是否有project-all的权限
r($result9) && p() && e('0'); // 查看user1是否有execution-all的权限