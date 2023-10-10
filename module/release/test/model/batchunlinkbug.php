#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->batchUnlinkBug();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

$release = zdTable('release')->config('release');
$release->bugs->range('`1,2,3`,`4,5,6`');
$release->leftBugs->range('`1,2,3`,`4,5,6`');
$release->gen(5);
zdTable('user')->gen(5);
su('admin');

$releases = array(0, 1, 6);
$types    = array('bug', 'leftBug');
$bugs[0]  = array(1, 2);
$bugs[1]  = array(4, 5);

$releaseTester = new releaseTest();
r($releaseTester->batchUnlinkBugTest($releases[0], $types[0], $bugs[0])) && p()                   && e('0');       // 测试发布ID为空时，解除跟bugID=1,2的关联
r($releaseTester->batchUnlinkBugTest($releases[1], $types[0], $bugs[0])) && p('0:old;0:new', ';') && e('1,2,3;3'); // 测试发布ID=1时，解除跟bugID=1,2的关联
r($releaseTester->batchUnlinkBugTest($releases[2], $types[0], $bugs[0])) && p()                   && e('0');       // 测试发布ID不存在时，解除跟bugID=1,2的关联
r($releaseTester->batchUnlinkBugTest($releases[0], $types[0], $bugs[1])) && p()                   && e('0');       // 测试发布ID为空时，解除跟bugID=4,5的关联
r($releaseTester->batchUnlinkBugTest($releases[1], $types[0], $bugs[1])) && p()                   && e('0');       // 测试发布ID=1时，解除跟bugID=4,5的关联
r($releaseTester->batchUnlinkBugTest($releases[2], $types[0], $bugs[1])) && p()                   && e('0');       // 测试发布ID不存在时，解除跟bugID=4,5的关联
r($releaseTester->batchUnlinkBugTest($releases[0], $types[1], $bugs[0])) && p()                   && e('0');       // 测试发布ID为空时，解除跟遗留的bugID=1,2的关联
r($releaseTester->batchUnlinkBugTest($releases[1], $types[1], $bugs[0])) && p('0:old;0:new', ';') && e('1,2,3;3'); // 测试发布ID=1时，解除跟遗留的bugID=1,2的关联
r($releaseTester->batchUnlinkBugTest($releases[2], $types[1], $bugs[0])) && p()                   && e('0');       // 测试发布ID不存在时，解除跟遗留的bugID=1,2的关联
r($releaseTester->batchUnlinkBugTest($releases[0], $types[1], $bugs[1])) && p()                   && e('0');       // 测试发布ID为空时，解除跟遗留的bugID=4,5的关联
r($releaseTester->batchUnlinkBugTest($releases[1], $types[1], $bugs[1])) && p()                   && e('0');       // 测试发布ID=1时，解除跟遗留的bugID=4,5的关联
r($releaseTester->batchUnlinkBugTest($releases[2], $types[1], $bugs[1])) && p()                   && e('0');       // 测试发布ID不存在时，解除跟遗留的bugID=4,5的关联
