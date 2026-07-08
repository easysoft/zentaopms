#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::__call();
timeout=0
cid=0

- 步骤 1：调用驼峰大小写不同的已有方法时返回与原方法一致的结果 @cached-magic
- 步骤 2：传入包含 project 子串的方法名时映射为 repo 同名方法并返回缓存对象 @cached-magic
- 步骤 3：传入完全不存在的方法名时返回 null @0
- 步骤 4：传入完全不存在的方法名时不会写入 dao 错误 @0
- 步骤 5：传入大小写混合的合法方法名时仍能解析并返回缓存对象 @cached-magic

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
zenData('pipeline')->loadYaml('pipeline')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

$cached = new stdclass();
$cached->id   = 7;
$cached->name = 'cached-magic';
$gitfoxTest->setRepoCache(7, $cached);

r($gitfoxTest->__callTest('apiGetSingleRepo', array(7))) && p('name') && e('cached-magic'); // 步骤 1
r($gitfoxTest->__callTest('apiGetSingleProject', array(7))) && p('name') && e('cached-magic'); // 步骤 2
r($gitfoxTest->__callTest('thisMethodDoesNotExist', array(1))) && p() && e('0'); // 步骤 3
r((int)dao::isError()) && p() && e('0'); // 步骤 4
r($gitfoxTest->__callTest('APIgetSINGLErepo', array(7))) && p('name') && e('cached-magic'); // 步骤 5
