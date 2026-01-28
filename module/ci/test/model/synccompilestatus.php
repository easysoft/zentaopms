#!/usr/bin/env php
<?php

/**

title=测试 ciModel->syncCompileStatus();
timeout=0
cid=15593

- 同步jenkins构建结果属性status @created
- 同步gitlab构建结果属性status @failed
- 5次请求没有拿到结果，构建失败属性status @failure
- 同步jenkins构建结果，有合并请求ID属性status @created
- 5次请求没有拿到结果，通知MR失败属性status @failure

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(3);
zenData('job')->loadYaml('job')->gen(10);
zenData('compile')->loadYaml('compile')->gen(10);
su('admin');

libxml_use_internal_errors(true);
$ci = new ciModelTest();

r($ci->syncCompileStatusTest(1)) && p('status') && e('created');  // 同步jenkins构建结果
r($ci->syncCompileStatusTest(2)) && p('status') && e('failed');  // 同步gitlab构建结果

r($ci->syncCompileStatusTest(6)) && p('status') && e('failure'); // 5次请求没有拿到结果，构建失败

r($ci->syncCompileStatusTest(1, 1)) && p('status') && e('created'); // 同步jenkins构建结果，有合并请求ID
r($ci->syncCompileStatusTest(6, 1)) && p('status') && e('failure'); // 5次请求没有拿到结果，通知MR失败
