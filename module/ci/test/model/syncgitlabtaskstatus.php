#!/usr/bin/env php
<?php

/**

title=测试 ciModel->syncGitlabTaskStatus();
timeout=0
cid=15594

- 同步jenkins流水线状态属性lastStatus @create_fail
- 同步gitlab流水线失败状态属性lastStatus @failed
- 同步jenkins流水线状态属性lastStatus @create_fail
- 同步gitlab流水线成功状态属性lastStatus @success
- 同步gitlab流水线成功状态属性lastStatus @create_fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ci.unittest.class.php';

zenData('pipeline')->gen(3);
zenData('job')->loadYaml('job')->gen(5);
zenData('compile')->loadYaml('compile')->gen(5);
zenData('mr')->loadYaml('mr')->gen(3);
su('admin');

$ci = new ciTest();

r($ci->syncGitlabTaskStatusTest(1)) && p('lastStatus') && e('create_fail'); // 同步jenkins流水线状态
r($ci->syncGitlabTaskStatusTest(2)) && p('lastStatus') && e('failed');      // 同步gitlab流水线失败状态
r($ci->syncGitlabTaskStatusTest(3)) && p('lastStatus') && e('create_fail'); // 同步jenkins流水线状态
r($ci->syncGitlabTaskStatusTest(4)) && p('lastStatus') && e('success');     // 同步gitlab流水线成功状态
r($ci->syncGitlabTaskStatusTest(5)) && p('lastStatus') && e('create_fail'); // 同步gitlab流水线成功状态
