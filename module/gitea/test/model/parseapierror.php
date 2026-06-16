#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::parseApiError();
timeout=0
cid=16569

- 测试步骤1：解析未知错误消息 @unknown error message
- 测试步骤2：解析已知API错误消息属性name @名称已存在。
- 测试步骤3：解析空字符串错误消息 @1
- 测试步骤4：解析特殊字符错误消息 @1
- 测试步骤5：解析已知错误后验证字段映射 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$giteaTest = new giteaModelTest();

$unknownError = $giteaTest->parseApiErrorTester('unknown error message');
$knownError   = $giteaTest->parseApiErrorTester('The repository with the same name already exists.');
$emptyError   = $giteaTest->parseApiErrorTester('');
$specialError = $giteaTest->parseApiErrorTester('Special chars: <>&"\'');
$mappedError  = $giteaTest->parseApiErrorTester('The repository with the same name already exists.');

r($unknownError) && p('0') && e('unknown error message'); // 测试步骤1：解析未知错误消息
r($knownError) && p('name') && e('名称已存在。'); // 测试步骤2：解析已知API错误消息
r(array_key_exists(0, $emptyError) && $emptyError[0] === '') && p() && e('1'); // 测试步骤3：解析空字符串错误消息
r(array_key_exists(0, $specialError) && $specialError[0] === 'Special chars: <>&"\'') && p() && e('1'); // 测试步骤4：解析特殊字符错误消息
r(isset($mappedError['name']) && $mappedError['name'] === '名称已存在。' && count($mappedError) === 1) && p() && e('1'); // 测试步骤5：解析已知错误后验证字段映射
