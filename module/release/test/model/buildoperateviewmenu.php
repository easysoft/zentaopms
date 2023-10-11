#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->buildOperateViewMenu();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

$release = zdTable('release')->config('release');
$release->status->range('normal,terminate');
$release->deleted->range('0{2},1');
$release->gen(5);

zdTable('user')->gen(5);
su('admin');

$releases = array(1, 2, 3);

$releaseTester = new releaseTest();
r($releaseTester->buildOperateViewMenuTest($releases[0])) && p('0:text') && e('停止维护'); // 测试获取正常状态的发布的操作列表
r($releaseTester->buildOperateViewMenuTest($releases[1])) && p('0:text') && e('激活');     // 测试获取停止维护状态的发布的操作列表
r($releaseTester->buildOperateViewMenuTest($releases[2])) && p()         && e('0');        // 测试获取已删除的发布的操作列表
