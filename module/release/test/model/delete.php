#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->delete();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

zdTable('build')->config('build')->gen(5);
zdTable('release')->config('release')->gen(5);
zdTable('user')->gen(5);
su('admin');

$releases = array(0, 1, 6);

$releaseTester = new releaseTest();
r($releaseTester->deleteTest($releases[0])) && p()          && e('0'); // 测试删除发布ID为0的发布
r($releaseTester->deleteTest($releases[1])) && p('deleted') && e('1'); // 测试删除发布ID为1的发布
r($releaseTester->deleteTest($releases[2])) && p()          && e('0'); // 测试删除发布ID不存在的发布
