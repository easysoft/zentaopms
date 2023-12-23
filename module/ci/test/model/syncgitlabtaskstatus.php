#!/usr/bin/env php
<?php

/**

title=测试 ciModel->syncGitlabTaskStatus();
timeout=0
cid=1

- 同步jenkins流水线状态属性lastStatus @create_fail
- 同步gitlab流水线失败状态属性lastStatus @failed
- 同步gitlab流水线成功状态属性lastStatus @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/ci.class.php';

zdTable('pipeline')->gen(3);
zdTable('job')->config('job')->gen(5);
zdTable('compile')->config('compile')->gen(5);
zdTable('mr')->config('mr')->gen(3);
su('admin');

$ci = new ciTest();

r($ci->syncGitlabTaskStatusTest(1)) && p('lastStatus') && e('create_fail'); // 同步jenkins流水线状态
r($ci->syncGitlabTaskStatusTest(2)) && p('lastStatus') && e('failed');      // 同步gitlab流水线失败状态
r($ci->syncGitlabTaskStatusTest(4)) && p('lastStatus') && e('success');     // 同步gitlab流水线成功状态