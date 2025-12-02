#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->buildActionList();
timeout=0
cid=0

- 测试获取正常状态的发布单的操作列表
 -  @linkStory
 - 属性1 @linkBug
 - 属性2 @pause
 - 属性3 @edit
 - 属性4 @notify
 - 属性5 @delete
- 测试获取停止维护状态的发布单的操作列表
 -  @linkStory
 - 属性1 @linkBug
 - 属性2 @play
 - 属性3 @edit
 - 属性4 @notify
 - 属性5 @delete

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

$release = zenData('release')->loadYaml('release');
$release->status->range('normal,terminate');
$release->gen(5);

zenData('user')->gen(5);
su('admin');

$releases = array(1, 2);

$releaseTester = new releaseTest();
r($releaseTester->buildActionListTest($releases[0])) && p('0,1,2,3,4,5') && e('linkStory,linkBug,pause,edit,notify,delete'); // 测试获取正常状态的发布单的操作列表
r($releaseTester->buildActionListTest($releases[1])) && p('0,1,2,3,4,5') && e('linkStory,linkBug,play,edit,notify,delete');  // 测试获取停止维护状态的发布单的操作列表