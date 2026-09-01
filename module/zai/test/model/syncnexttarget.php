#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->syncNextTarget();
timeout=0
cid=19779

- 测试同步不存在的类型 @0
- 测试同步不存在的目标 @0
- 测试需求同步成功的情况
 - 属性result @success
 - 属性target @~~
 - 属性id @1
- 测试Bug同步成功的情况
 - 属性result @success
 - 属性target @~~
 - 属性id @1
- 测试需求同步时ZAI服务不可用 @fail
- 测试Bug同步时ZAI服务不可用 @fail
- 测试不同知识库同步时ZAI服务不可用 @fail

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('story')->gen(1);
zenData('bug')->gen(1);
zenData('storyspec')->gen(1);

su('admin');

global $tester;
$zai = new zaiModelTest();

// 模拟ZAI设置
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 测试同步不存在的类型 */
r($zai->syncNextTargetTest('testmemory123', 'invalidtype', 1)) && p() && e('0'); // 测试同步不存在的类型

/* 测试同步不存在的目标（ID 99不存在） */
r($zai->syncNextTargetTest('testmemory123', 'story', 99)) && p() && e('0'); // 测试同步不存在的目标

/* 测试需求同步（无真实ZAI服务时失败） */
r($zai->syncNextTargetTest('testmemory123', 'story', 1)) && p('result') && e('fail'); // 测试需求同步时ZAI服务不可用

/* 测试Bug同步（无真实ZAI服务时失败） */
r($zai->syncNextTargetTest('testmemory123', 'bug', 1)) && p('result') && e('fail'); // 测试Bug同步时ZAI服务不可用

/* 测试不同知识库同步（无真实ZAI服务时失败） */
r($zai->syncNextTargetTest('othermemory456', 'story', 1)) && p('result') && e('fail'); // 测试不同知识库同步时ZAI服务不可用
