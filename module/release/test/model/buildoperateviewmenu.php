#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->buildOperateViewMenu();
timeout=0
cid=1

- 测试获取正常状态的发布的操作列表
 - 第0条的text属性 @停止维护
 - 第0条的icon属性 @pause
- 测试获取停止维护状态的发布的操作列表
 - 第0条的text属性 @激活
 - 第0条的icon属性 @play
- 测试获取已删除的发布的操作列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

$release = zenData('release')->loadYaml('release');
$release->status->range('normal,terminate');
$release->deleted->range('0{2},1');
$release->gen(5);

zenData('user')->gen(5);
su('admin');

$releases = array(1, 2, 3);

$releaseTester = new releaseTest();
r($releaseTester->buildOperateViewMenuTest($releases[0])) && p('0:text,icon') && e('停止维护,pause'); // 测试获取正常状态的发布的操作列表
r($releaseTester->buildOperateViewMenuTest($releases[1])) && p('0:text,icon') && e('激活,play');      // 测试获取停止维护状态的发布的操作列表
r($releaseTester->buildOperateViewMenuTest($releases[2])) && p()              && e('0');              // 测试获取已删除的发布的操作列表