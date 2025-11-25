#!/usr/bin/env php
<?php

/**

title=测试 mrModel::createMRLinkedAction();
timeout=0
cid=17242

- 不存在的合并请求 @0
- ID为1的合并请求关联的需求记录动态数量 @10
- ID为1的合并请求关联的需求记录动态内容
 - 第0条的objectType属性 @story
 - 第0条的actor属性 @admin
 - 第0条的action属性 @createmr

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

su('admin');

zenData('action')->gen(0);
zenData('mr')->gen(1);
zenData('relation')->loadYaml('relation')->gen(10);

$mrModel = new mrTest();
r($mrModel->createMRLinkedActionTester(2)) && p() && e('0'); // 不存在的合并请求

$result = $mrModel->createMRLinkedActionTester(1);
r(count($result)) && p() && e('10'); // ID为1的合并请求关联的需求记录动态数量
r($result)        && p('0:objectType,actor,action') && e('story,admin,createmr'); // ID为1的合并请求关联的需求记录动态内容