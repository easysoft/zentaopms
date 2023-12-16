#!/usr/bin/env php
<?php

/**

title=测试 mrModel->getLinkedObjectPairs();
timeout=0
cid=1

- 不存在的合并请求 @0
- ID为1的合并请求关联的需求 @4|1|7|10
- ID为1的合并请求关联的任务 @2|8|5
- ID为1的合并请求关联的bug @3|6|9

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('relation')->config('relation')->gen(10);
su('admin');

global $tester;
$mrModel = $tester->loadModel('mr');

r($mrModel->getLinkedObjectPairs(2, 'story')) && p() && e('0'); // 不存在的合并请求

r(implode('|', $mrModel->getLinkedObjectPairs(1, 'story'))) && p() && e('4|1|7|10'); // ID为1的合并请求关联的需求
r(implode('|', $mrModel->getLinkedObjectPairs(1, 'task')))  && p() && e('2|8|5');    // ID为1的合并请求关联的任务
r(implode('|', $mrModel->getLinkedObjectPairs(1, 'bug')))   && p() && e('3|6|9');    // ID为1的合并请求关联的bug
