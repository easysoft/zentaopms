#!/usr/bin/env php
<?php

/**

title=测试 mrModel->createMRLinkedAction();
timeout=0
cid=1

- 不存在的合并请求 @0
- ID为1的合并请求关联的需求记录动态数量 @10
- ID为1的合并请求关联的需求记录动态内容
 - 第0条的objectType属性 @story
 - 第0条的actor属性 @admin
 - 第0条的action属性 @createmr

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('action')->gen(0);
zdTable('relation')->config('relation')->gen(10);
su('admin');

$mrModel = new mrTest();

r($mrModel->createMRLinkedActionTester(2)) && p() && e('0');        // 不存在的合并请求

$result = $mrModel->createMRLinkedActionTester(1);
r(count($result)) && p() && e('10'); // ID为1的合并请求关联的需求记录动态数量
r($result)        && p('0:objectType,actor,action') && e('story,admin,createmr'); // ID为1的合并请求关联的需求记录动态内容