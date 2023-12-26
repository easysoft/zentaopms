#!/usr/bin/env php
<?php

/**

title=测试 ciModel->getCompileByID();
timeout=0
cid=1

- 流水线ID为空 @0
- 正常的流水线ID属性status @created
- 不存在的流水线ID属性status @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/ci.class.php';

zdTable('pipeline')->gen(3);
zdTable('job')->config('job')->gen(5);
zdTable('compile')->config('compile')->gen(3);
zdTable('mr')->gen(0);
su('admin');

$ci = new ciTest();

r($ci->getCompileByIdTest(0)) && p()         && e('0');       // 流水线ID为空
r($ci->getCompileByIdTest(1)) && p('status') && e('created'); // 正常的流水线ID
r($ci->getCompileByIdTest(4)) && p('status') && e('0');       // 不存在的流水线ID