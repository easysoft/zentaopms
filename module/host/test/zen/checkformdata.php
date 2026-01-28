#!/usr/bin/env php
<?php

/**

title=测试 hostZen::checkFormData();
timeout=0
cid=16764

- 步骤1：正常情况 @1
- 步骤2：名称长度边界值属性name @主机名称长度不能超过100个字符！
- 步骤3：描述长度边界值属性desc @主机描述长度不能超过255个字符！
- 步骤4：整数字段验证属性diskSize @硬盘容量只能为数字！
- 步骤5：IP格式验证属性intranet @『内网IP』格式不正确！

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$hostTest = new hostZenTest();

// 4. 测试步骤1：正常输入数据验证
$validFormData = new stdClass();
$validFormData->name = '测试主机';
$validFormData->desc = '这是一个测试主机的描述';
$validFormData->diskSize = '100';
$validFormData->memory = '8';
$validFormData->intranet = '192.168.1.100';
$validFormData->extranet = '8.8.8.8';
r($hostTest->checkFormDataTest($validFormData)) && p() && e('1'); // 步骤1：正常情况

// 5. 测试步骤2：主机名称长度超过100个字符
$longNameFormData = new stdClass();
$longNameFormData->name = str_repeat('主机名称', 30); // 超过100个字符
$longNameFormData->desc = '正常描述';
$longNameFormData->diskSize = '100';
$longNameFormData->memory = '8';
$longNameFormData->intranet = '192.168.1.100';
$longNameFormData->extranet = '8.8.8.8';
r($hostTest->checkFormDataTest($longNameFormData)) && p('name') && e('主机名称长度不能超过100个字符！'); // 步骤2：名称长度边界值

// 6. 测试步骤3：描述长度超过255个字符
$longDescFormData = new stdClass();
$longDescFormData->name = '测试主机';
$longDescFormData->desc = str_repeat('描述内容', 70); // 超过255个字符（280字符）
$longDescFormData->diskSize = '100';
$longDescFormData->memory = '8';
$longDescFormData->intranet = '192.168.1.100';
$longDescFormData->extranet = '8.8.8.8';
r($hostTest->checkFormDataTest($longDescFormData)) && p('desc') && e('主机描述长度不能超过255个字符！'); // 步骤3：描述长度边界值

// 7. 测试步骤4：整数字段输入非数字内容
$invalidIntFormData = new stdClass();
$invalidIntFormData->name = '测试主机';
$invalidIntFormData->desc = '正常描述';
$invalidIntFormData->diskSize = 'abc';
$invalidIntFormData->memory = '8';
$invalidIntFormData->intranet = '192.168.1.100';
$invalidIntFormData->extranet = '8.8.8.8';
r($hostTest->checkFormDataTest($invalidIntFormData)) && p('diskSize') && e('硬盘容量只能为数字！'); // 步骤4：整数字段验证

// 8. 测试步骤5：IP地址字段输入无效IP
$invalidIpFormData = new stdClass();
$invalidIpFormData->name = '测试主机';
$invalidIpFormData->desc = '正常描述';
$invalidIpFormData->diskSize = '100';
$invalidIpFormData->memory = '8';
$invalidIpFormData->intranet = '999.999.999.999';
$invalidIpFormData->extranet = '8.8.8.8';
r($hostTest->checkFormDataTest($invalidIpFormData)) && p('intranet') && e('『内网IP』格式不正确！'); // 步骤5：IP格式验证