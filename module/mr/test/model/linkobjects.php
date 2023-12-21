#!/usr/bin/env php
<?php

/**

title=测试 mrModel::linkObjects();
timeout=0
cid=0

- 错误的合并请求 @0
- 正确的合并请求 @3
- 正确的合并请求
 - 第1条的BType属性 @story
 - 第1条的BID属性 @2
 - 第2条的BType属性 @bug
 - 第2条的BID属性 @3
 - 第3条的BType属性 @task
 - 第3条的BID属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('bug')->gen(5);
zdTable('task')->gen(5);
zdTable('story')->gen(5);
zdTable('pipeline')->gen(5);
zdTable('relation')->gen(0);
zdTable('mr')->config('mr')->gen(5);

$mrModel = new mrTest();

r($mrModel->linkObjectsTester(4)) && p() && e('0'); // 错误的合并请求

$result = $mrModel->linkObjectsTester(1);
r(count($result)) && p() && e('3'); // 正确的合并请求
r($result) && p('1:BType,BID;2:BType,BID;3:BType,BID') && e('story,2,bug,3,task,1'); // 正确的合并请求