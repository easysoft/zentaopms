#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->batchUnlinkStory();
timeout=0
cid=17981

- 测试发布ID为空时，解除跟需求ID=1,2的关联 @0
- 测试发布ID=1时，解除跟需求ID=1,2的关联
 - 第0条的old属性 @1,2,3
 - 第0条的new属性 @3
- 测试发布ID不存在时，解除跟需求ID=1,2的关联 @0
- 测试发布ID为空时，解除跟需求ID=4,5的关联 @0
- 测试发布ID=1时，解除跟需求ID=4,5的关联 @0
- 测试发布ID不存在时，解除跟需求ID=4,5的关联 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$release = zenData('release')->loadYaml('release');
$release->stories->range('`1,2,3`,`4,5,6`');
$release->gen(5);
zenData('user')->gen(5);
su('admin');

$releases   = array(0, 1, 6);
$stories[0] = array(1, 2);
$stories[1] = array(4, 5);

$releaseTester = new releaseModelTest();
r($releaseTester->batchUnlinkStoryTest($releases[0], $stories[0])) && p()                   && e('0');       // 测试发布ID为空时，解除跟需求ID=1,2的关联
r($releaseTester->batchUnlinkStoryTest($releases[1], $stories[0])) && p('0:old;0:new', ';') && e('1,2,3;3'); // 测试发布ID=1时，解除跟需求ID=1,2的关联
r($releaseTester->batchUnlinkStoryTest($releases[2], $stories[0])) && p()                   && e('0');       // 测试发布ID不存在时，解除跟需求ID=1,2的关联
r($releaseTester->batchUnlinkStoryTest($releases[0], $stories[1])) && p()                   && e('0');       // 测试发布ID为空时，解除跟需求ID=4,5的关联
r($releaseTester->batchUnlinkStoryTest($releases[1], $stories[1])) && p()                   && e('0');       // 测试发布ID=1时，解除跟需求ID=4,5的关联
r($releaseTester->batchUnlinkStoryTest($releases[2], $stories[1])) && p()                   && e('0');       // 测试发布ID不存在时，解除跟需求ID=4,5的关联