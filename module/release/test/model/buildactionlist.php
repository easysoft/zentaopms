#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->buildActionList();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

$release = zdTable('release')->config('release');
$release->status->range('normal,terminate');
$release->gen(5);

zdTable('user')->gen(5);
su('admin');

$releases = array(1, 2);

$releaseTester = new releaseTest();
r($releaseTester->buildActionListTest($releases[0])) && p('0,1,2,3,4,5') && e('linkStory,linkBug,pause,edit,notify,delete'); // 测试获取正常状态的发布单的操作列表
r($releaseTester->buildActionListTest($releases[1])) && p('0,1,2,3,4,5') && e('linkStory,linkBug,play,edit,notify,delete');  // 测试获取停止维护状态的发布单的操作列表
