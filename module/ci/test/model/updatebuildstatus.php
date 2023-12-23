#!/usr/bin/env php
<?php

/**

title=测试 ciModel->updateBuildStatus();
timeout=0
cid=1

- 更新构建任务状态为构建中属性status @building
- 更新构建任务状态为成功属性status @success
- 更新构建任务状态为失败属性status @failure
- 构建失败更新合并请求属性status @closed
- 构建成功更新合并请求属性status @opened

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/ci.class.php';

zdTable('pipeline')->gen(3);
zdTable('mr')->config('mr')->gen(10);
zdTable('job')->config('job')->gen(10);
zdTable('compile')->config('compile')->gen(10);
su('admin');

$ci = new ciTest();

r($ci->updateBuildStatusTest(1, 'building')) && p('status') && e('building'); // 更新构建任务状态为构建中
r($ci->updateBuildStatusTest(1, 'success'))  && p('status') && e('success');  // 更新构建任务状态为成功
r($ci->updateBuildStatusTest(1, 'failure'))  && p('status') && e('failure');  // 更新构建任务状态为失败

r($ci->updateBuildStatusTest(2, 'failure', 'mr')) && p('status') && e('closed'); // 构建失败更新合并请求
r($ci->updateBuildStatusTest(3, 'success', 'mr')) && p('status') && e('opened'); // 构建成功更新合并请求