#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::parseApiError();
timeout=0
cid=16569

- 测试步骤1：解析未知错误消息 @unknown error message
- 测试步骤2：解析已知API错误消息属性name @名称已存在。
- 测试步骤3：解析空字符串错误消息 @
- 测试步骤4：解析特殊字符错误消息 @Special chars: <>&"\
- 测试步骤5：解析已知错误后验证字段映射 @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$giteaTest = new giteaModelTest();

r($giteaTest->parseApiErrorTester('unknown error message')) && p('0') && e('unknown error message'); // 测试步骤1：解析未知错误消息
r($giteaTest->parseApiErrorTester('The repository with the same name already exists.')) && p('name') && e('名称已存在。'); // 测试步骤2：解析已知API错误消息
r($giteaTest->parseApiErrorTester('')) && p('0') && e(''); // 测试步骤3：解析空字符串错误消息
r($giteaTest->parseApiErrorTester('Special chars: <>&"\'')) && p('0') && e('Special chars: <>&"\''); // 测试步骤4：解析特殊字符错误消息
r($giteaTest->parseApiErrorTester('The repository with the same name already exists.')) && p() && e('~~'); // 测试步骤5：解析已知错误后验证字段映射