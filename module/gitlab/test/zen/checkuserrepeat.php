#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::checkUserRepeat();
timeout=0
cid=0

- 测试无重复用户的情况属性result @success
- 测试有一个重复用户的情况,返回失败属性result @fail
- 测试有多个重复用户的情况,返回失败属性result @fail
- 测试空用户数组的情况属性result @success
- 测试包含空值的用户数组属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/* 设置 methodName 避免 gitlab 控制器构造函数报错 */
global $app;
$app->setMethodName('test');

$gitlabTest = new gitlabZenTest();

r($gitlabTest->checkUserRepeatTest(array(100 => 'admin', 200 => 'user1', 300 => 'user2'), array('admin' => '管理员', 'user1' => '用户1', 'user2' => '用户2'))) && p('result') && e('success'); // 测试无重复用户的情况
r($gitlabTest->checkUserRepeatTest(array(100 => 'admin', 200 => 'admin', 300 => 'user2'), array('admin' => '张三', 'user2' => '用户2'))) && p('result') && e('fail'); // 测试有一个重复用户的情况,返回失败
r($gitlabTest->checkUserRepeatTest(array(100 => 'admin', 200 => 'admin', 300 => 'user1', 400 => 'user1'), array('admin' => '张三', 'user1' => '李四'))) && p('result') && e('fail'); // 测试有多个重复用户的情况,返回失败
r($gitlabTest->checkUserRepeatTest(array(), array())) && p('result') && e('success'); // 测试空用户数组的情况
r($gitlabTest->checkUserRepeatTest(array(100 => 'admin', 200 => '', 300 => 'user1'), array('admin' => '管理员', 'user1' => '用户1'))) && p('result') && e('success'); // 测试包含空值的用户数组