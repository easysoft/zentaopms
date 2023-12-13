#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->isClickable();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

$release = zdTable('release')->config('release');
$release->stories->range('`1,2,3`,[]');
$release->bugs->range('`1,2,3`,[]');
$release->gen(5);

zdTable('user')->gen(5);
su('admin');

$releases = array(1, 2);
$actions  = array('linkStory', 'unlinkStory', 'linkBug', 'unlinkBug', 'unlinkLeftBug', 'play', 'pause', 'edit', 'notify', 'delete');

$releaseTester = new releaseTest();
r($releaseTester->isClickableTest($releases[0], $actions[0])) && p() && e('1'); // 测试关联需求和Bug的发布是否可以点击关联需求按钮
r($releaseTester->isClickableTest($releases[0], $actions[1])) && p() && e('1'); // 测试关联需求和Bug的发布是否可以点击取消关联需求按钮
r($releaseTester->isClickableTest($releases[0], $actions[2])) && p() && e('1'); // 测试关联需求和Bug的发布是否可以点击关联BUG按钮
r($releaseTester->isClickableTest($releases[0], $actions[3])) && p() && e('1'); // 测试关联需求和Bug的发布是否可以点击取消关联BUG按钮
r($releaseTester->isClickableTest($releases[0], $actions[4])) && p() && e('1'); // 测试关联需求和Bug的发布是否可以点击取消关联遗留BUG按钮
r($releaseTester->isClickableTest($releases[0], $actions[5])) && p() && e('0'); // 测试关联需求和Bug的发布是否可以点击激活按钮
r($releaseTester->isClickableTest($releases[0], $actions[6])) && p() && e('1'); // 测试关联需求和Bug的发布是否可以点击停止维护按钮
r($releaseTester->isClickableTest($releases[0], $actions[7])) && p() && e('1'); // 测试关联需求和Bug的发布是否可以点击编辑按钮
r($releaseTester->isClickableTest($releases[0], $actions[8])) && p() && e('1'); // 测试关联需求和Bug的发布是否可以点击通知按钮
r($releaseTester->isClickableTest($releases[0], $actions[9])) && p() && e('1'); // 测试关联需求和Bug的发布是否可以点击删除按钮
r($releaseTester->isClickableTest($releases[1], $actions[0])) && p() && e('1'); // 测试未关联需求和Bug的发布是否可以点击关联需求按钮
r($releaseTester->isClickableTest($releases[1], $actions[1])) && p() && e('1'); // 测试未关联需求和Bug的发布是否可以点击取消关联需求按钮
r($releaseTester->isClickableTest($releases[1], $actions[2])) && p() && e('1'); // 测试未关联需求和Bug的发布是否可以点击关联BUG按钮
r($releaseTester->isClickableTest($releases[1], $actions[3])) && p() && e('1'); // 测试未关联需求和Bug的发布是否可以点击取消关联BUG按钮
r($releaseTester->isClickableTest($releases[1], $actions[4])) && p() && e('1'); // 测试未关联需求和Bug的发布是否可以点击取消关联遗留BUG按钮
r($releaseTester->isClickableTest($releases[1], $actions[5])) && p() && e('0'); // 测试未关联需求和Bug的发布是否可以点击激活按钮
r($releaseTester->isClickableTest($releases[1], $actions[6])) && p() && e('1'); // 测试未关联需求和Bug的发布是否可以点击停止维护按钮
r($releaseTester->isClickableTest($releases[1], $actions[7])) && p() && e('1'); // 测试未关联需求和Bug的发布是否可以点击编辑按钮
r($releaseTester->isClickableTest($releases[1], $actions[8])) && p() && e('0'); // 测试未关联需求和Bug的发布是否可以点击通知按钮
r($releaseTester->isClickableTest($releases[1], $actions[9])) && p() && e('1'); // 测试未关联需求和Bug的发布是否可以点击删除按钮
