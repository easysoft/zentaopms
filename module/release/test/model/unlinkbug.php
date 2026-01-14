#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->unlinkBug();
timeout=0
cid=18015

- 测试发布ID为空时，解除跟bugID=2的关联 @0
- 测试发布ID=1时，解除跟bugID=2的关联
 - 第0条的old属性 @1,2,3
 - 第0条的new属性 @1,3
- 测试发布ID不存在时，解除跟bugID=2的关联 @0
- 测试发布ID为空时，解除跟bugID=4的关联 @0
- 测试发布ID=1时，解除跟bugID=4的关联 @0
- 测试发布ID不存在时，解除跟bugID=4的关联 @0
- 测试发布ID为空时，解除跟遗留的bugID=2的关联 @0
- 测试发布ID=1时，解除跟遗留的bugID=2的关联
 - 第0条的old属性 @1,2,3
 - 第0条的new属性 @1,3
- 测试发布ID不存在时，解除跟遗留的bugID=2的关联 @0
- 测试发布ID为空时，解除跟遗留的bugID=4的关联 @0
- 测试发布ID=1时，解除跟遗留的bugID=4的关联 @0
- 测试发布ID不存在时，解除跟遗留的bugID=4的关联 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$release = zenData('release')->loadYaml('release');
$release->bugs->range('`1,2,3`,`4,5,6`');
$release->leftBugs->range('`1,2,3`,`4,5,6`');
$release->gen(5);
zenData('user')->gen(5);
su('admin');

$releases = array(0, 1, 6);
$types    = array('bug', 'leftBug');
$bugs     = array(2, 4);

$releaseTester = new releaseModelTest();
r($releaseTester->unlinkBugTest($releases[0], $bugs[0], $types[0])) && p()                   && e('0');         // 测试发布ID为空时，解除跟bugID=2的关联
r($releaseTester->unlinkBugTest($releases[1], $bugs[0], $types[0])) && p('0:old;0:new', ';') && e('1,2,3;1,3'); // 测试发布ID=1时，解除跟bugID=2的关联
r($releaseTester->unlinkBugTest($releases[2], $bugs[0], $types[0])) && p()                   && e('0');         // 测试发布ID不存在时，解除跟bugID=2的关联
r($releaseTester->unlinkBugTest($releases[0], $bugs[1], $types[0])) && p()                   && e('0');         // 测试发布ID为空时，解除跟bugID=4的关联
r($releaseTester->unlinkBugTest($releases[1], $bugs[1], $types[0])) && p()                   && e('0');         // 测试发布ID=1时，解除跟bugID=4的关联
r($releaseTester->unlinkBugTest($releases[2], $bugs[1], $types[0])) && p()                   && e('0');         // 测试发布ID不存在时，解除跟bugID=4的关联
r($releaseTester->unlinkBugTest($releases[0], $bugs[0], $types[1])) && p()                   && e('0');         // 测试发布ID为空时，解除跟遗留的bugID=2的关联
r($releaseTester->unlinkBugTest($releases[1], $bugs[0], $types[1])) && p('0:old;0:new', ';') && e('1,2,3;1,3'); // 测试发布ID=1时，解除跟遗留的bugID=2的关联
r($releaseTester->unlinkBugTest($releases[2], $bugs[0], $types[1])) && p()                   && e('0');         // 测试发布ID不存在时，解除跟遗留的bugID=2的关联
r($releaseTester->unlinkBugTest($releases[0], $bugs[1], $types[1])) && p()                   && e('0');         // 测试发布ID为空时，解除跟遗留的bugID=4的关联
r($releaseTester->unlinkBugTest($releases[1], $bugs[1], $types[1])) && p()                   && e('0');         // 测试发布ID=1时，解除跟遗留的bugID=4的关联
r($releaseTester->unlinkBugTest($releases[2], $bugs[1], $types[1])) && p()                   && e('0');         // 测试发布ID不存在时，解除跟遗留的bugID=4的关联