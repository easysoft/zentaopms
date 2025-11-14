#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectrelease.unittest.class.php';

zenData('user')->gen(1);

su('admin');

/**

title=测试 projectreleaseModel->getLast();
cid=17972

- 测试获取 没有 需求 和 bug 的发布是否可以点击 notify 按钮 @2
- 测试获取 有 需求 没有 bug 的发布是否可以点击 notify 按钮 @1
- 测试获取 没有 需求 有 bug 的发布是否可以点击 notify 按钮 @1
- 测试获取 有 需求 和 bug 的发布是否可以点击 notify 按钮 @1
- 测试获取 状态为 terminate  的发布是否可以点击 play 按钮 @1
- 测试获取 状态为 normal  的发布是否可以点击 play 按钮 @2
- 测试获取 状态为 terminate  的发布是否可以点击 pause 按钮 @2
- 测试获取 状态为 normal  的发布是否可以点击 pause 按钮 @1
- 测试获取 release 1 的发布是否可以点击 edit 按钮 @1
- 测试获取 release 2 的发布是否可以点击 edit 按钮 @1
- 测试获取 release 3 的发布是否可以点击 edit 按钮 @1
- 测试获取 release 4  的发布是否可以点击 edit 按钮 @1

*/

$object = array(11, 12, 0, 1000);

$release1 = new stdclass();
$release1->bugs    = null;
$release1->stories = null;
$release1->status  = 'terminate';

$release2 = new stdclass();
$release2->bugs    = '1';
$release2->stories = null;
$release2->status  = 'normal';

$release3 = new stdclass();
$release3->bugs    = null;
$release3->stories = '1';
$release3->status  = 'normal';

$release4 = new stdclass();
$release4->bugs    = '1';
$release4->stories = '1';
$release4->status  = 'normal';

$actions = array('notify', 'play', 'pause', 'edit');

$projectrelease = new projectreleaseTest();

r($projectrelease->isClickableTest($release1, $actions[0])) && p() && e('2');  // 测试获取 没有 需求 和 bug 的发布是否可以点击 notify 按钮
r($projectrelease->isClickableTest($release2, $actions[0])) && p() && e('1');  // 测试获取 有 需求 没有 bug 的发布是否可以点击 notify 按钮
r($projectrelease->isClickableTest($release3, $actions[0])) && p() && e('1');  // 测试获取 没有 需求 有 bug 的发布是否可以点击 notify 按钮
r($projectrelease->isClickableTest($release4, $actions[0])) && p() && e('1');  // 测试获取 有 需求 和 bug 的发布是否可以点击 notify 按钮

r($projectrelease->isClickableTest($release1, $actions[1])) && p() && e('1');  // 测试获取 状态为 terminate  的发布是否可以点击 play 按钮
r($projectrelease->isClickableTest($release2, $actions[1])) && p() && e('2');  // 测试获取 状态为 normal  的发布是否可以点击 play 按钮

r($projectrelease->isClickableTest($release1, $actions[2])) && p() && e('2');  // 测试获取 状态为 terminate  的发布是否可以点击 pause 按钮
r($projectrelease->isClickableTest($release2, $actions[2])) && p() && e('1');  // 测试获取 状态为 normal  的发布是否可以点击 pause 按钮

r($projectrelease->isClickableTest($release1, $actions[3])) && p() && e('1');  // 测试获取 release 1 的发布是否可以点击 edit 按钮
r($projectrelease->isClickableTest($release2, $actions[3])) && p() && e('1');  // 测试获取 release 2 的发布是否可以点击 edit 按钮
r($projectrelease->isClickableTest($release3, $actions[3])) && p() && e('1');  // 测试获取 release 3 的发布是否可以点击 edit 按钮
r($projectrelease->isClickableTest($release4, $actions[3])) && p() && e('1');  // 测试获取 release 4  的发布是否可以点击 edit 按钮
