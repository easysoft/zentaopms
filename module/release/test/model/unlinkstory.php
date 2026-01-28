#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->unlinkStory();
timeout=0
cid=18016

- 测试发布ID为空时，解除跟需求ID=2的关联 @0
- 测试发布ID=1时，解除跟需求ID=2的关联
 - 第0条的old属性 @1,2,3
 - 第0条的new属性 @1,3
- 测试发布ID不存在时，解除跟需求ID=2的关联 @0
- 测试发布ID为空时，解除跟需求ID=4的关联 @0
- 测试发布ID=1时，解除跟需求ID=4的关联 @0
- 测试发布ID不存在时，解除跟需求ID=4的关联 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$release = zenData('release')->loadYaml('release');
$release->stories->range('`1,2,3`,`4,5,6`');
$release->gen(5);
zenData('user')->gen(5);
su('admin');

$releases = array(0, 1, 6);
$stories  = array(2, 4);

$releaseTester = new releaseModelTest();
r($releaseTester->unlinkStoryTest($releases[0], $stories[0])) && p()                   && e('0');         // 测试发布ID为空时，解除跟需求ID=2的关联
r($releaseTester->unlinkStoryTest($releases[1], $stories[0])) && p('0:old;0:new', ';') && e('1,2,3;1,3'); // 测试发布ID=1时，解除跟需求ID=2的关联
r($releaseTester->unlinkStoryTest($releases[2], $stories[0])) && p()                   && e('0');         // 测试发布ID不存在时，解除跟需求ID=2的关联
r($releaseTester->unlinkStoryTest($releases[0], $stories[1])) && p()                   && e('0');         // 测试发布ID为空时，解除跟需求ID=4的关联
r($releaseTester->unlinkStoryTest($releases[1], $stories[1])) && p()                   && e('0');         // 测试发布ID=1时，解除跟需求ID=4的关联
r($releaseTester->unlinkStoryTest($releases[2], $stories[1])) && p()                   && e('0');         // 测试发布ID不存在时，解除跟需求ID=4的关联
