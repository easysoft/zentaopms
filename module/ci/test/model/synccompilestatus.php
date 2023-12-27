#!/usr/bin/env php
<?php

/**

title=测试 ciModel->syncCompileStatus();
timeout=0
cid=1

- 同步jenkins构建结果属性status @success
- 同步gitlab构建结果属性status @failed
- 三次请求没有拿到结果，构建失败属性status @failure
- 同步jenkins构建结果，有合并请求ID属性status @success
- 三次请求没有拿到结果，通知MR失败属性status @failure

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/ci.class.php';

zdTable('pipeline')->gen(3);
zdTable('job')->config('job')->gen(10);
zdTable('compile')->config('compile')->gen(10);
su('admin');

$ci = new ciTest();

r($ci->syncCompileStatusTest(1)) && p('status') && e('success'); // 同步jenkins构建结果
r($ci->syncCompileStatusTest(2)) && p('status') && e('failed');  // 同步gitlab构建结果

r($ci->syncCompileStatusTest(4)) && p('status') && e('failure'); // 三次请求没有拿到结果，构建失败

r($ci->syncCompileStatusTest(1, 1)) && p('status') && e('success'); // 同步jenkins构建结果，有合并请求ID
r($ci->syncCompileStatusTest(5, 1)) && p('status') && e('failure'); // 三次请求没有拿到结果，通知MR失败
