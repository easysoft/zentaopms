#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::requestSystem();
timeout=0
cid=0

- 步骤1：以系统账号请求GitFox接口返回对象 @object
- 步骤2：请求成功的响应包含data属性 @1
- 步骤3：请求成功的响应data属性为数组 @array
- 步骤4：请求不存在的接口返回false @0
- 步骤5：不解析响应时返回原始响应对象 @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$gitfoxTest = new gitfoxModelTest();

$user = zenData('user');
$user->id->range('1');
$user->account->range('admin');
$user->realname->range('admin');
$user->password->range('e10adc3949ba59abbe56e057f20f883e');
$user->deleted->range('0');
$user->gen(1);

zenData('entry')->gen(0);
$entry = zenData('entry');
$entry->id->range('1');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->createdBy->range('admin');
$entry->createdDate->range('[2026-01-01 00:00:00]');
$entry->calledTime->range('0');
$entry->editedBy->range('admin');
$entry->editedDate->range('[2026-01-01 00:00:00]');
$entry->deleted->range('0');
$entry->gen(1);

su('admin');

r($gitfoxTest->requestSystemTypeTest('/spaces'))              && p() && e('object'); // 步骤1：以系统账号请求GitFox接口返回对象
r($gitfoxTest->requestSystemHasDataTest('/spaces'))           && p() && e('1');      // 步骤2：请求成功的响应包含data属性
r($gitfoxTest->requestSystemDataTypeTest('/spaces'))          && p() && e('array');  // 步骤3：请求成功的响应data属性为数组
r($gitfoxTest->requestSystemTest('/not-exists-endpoint'))     && p() && e('0');      // 步骤4：请求不存在的接口返回false
r($gitfoxTest->requestSystemRawTypeTest('/spaces'))           && p() && e('object'); // 步骤5：不解析响应时返回原始响应对象
