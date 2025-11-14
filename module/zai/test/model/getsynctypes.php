#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getSyncTypes();
timeout=0
cid=19774

- 测试获取可同步类型列表 @1
- 测试获取的类型数量 @1
- 测试story类型存在 @1
- 测试bug类型存在 @1
- 测试doc类型存在 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('config')->gen(0);

su('admin');

global $tester;
$zai = new zaiTest();

/* 测试获取可同步类型列表 */
$syncTypes = $zai->getSyncTypesTest();
r(isset($syncTypes['story'])) && p() && e('1'); // 测试获取可同步类型列表

/* 测试获取的类型数量 */
r(count($syncTypes) > 0) && p() && e('1'); // 测试获取的类型数量

/* 测试story类型存在 */
r(array_key_exists('story', $syncTypes)) && p() && e('1'); // 测试story类型存在

/* 测试bug类型存在 */
r(array_key_exists('bug', $syncTypes)) && p() && e('1'); // 测试bug类型存在

/* 测试doc类型存在 */
r(array_key_exists('doc', $syncTypes)) && p() && e('1'); // 测试doc类型存在
