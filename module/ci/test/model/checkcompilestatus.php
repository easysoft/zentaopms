#!/usr/bin/env php
<?php

/**

title=测试 ciModel->syncGitlabTaskStatus();
timeout=0
cid=1

- 不过滤构建任务
 - 第1条的name属性 @构建1
 - 第3条的status属性 @created
- 正常的构建任务属性status @created
- 不存在的构建任务属性status @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/ci.class.php';

zdTable('pipeline')->gen(3);
zdTable('job')->config('job')->gen(5);
zdTable('compile')->config('compile')->gen(3);
zdTable('mr')->gen(0);
su('admin');

$ci = new ciTest();

r($ci->checkCompileStatusTest(0)) && p('1:name;3:status') && e('构建1,created');  // 不过滤构建任务
r($ci->checkCompileStatusTest(1)) && p('status')          && e('created');        // 正常的构建任务
r($ci->checkCompileStatusTest(4)) && p('status')          && e('0');              // 不存在的构建任务