#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getSyncTypes();
cid=0

- 测试获取可同步类型列表 >> 期望返回包含story的数组
- 测试获取的类型数量 >> 期望返回大于0的数量
- 测试story类型存在 >> 期望story类型存在
- 测试bug类型存在 >> 期望bug类型存在
- 测试doc类型存在 >> 期望doc类型存在

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
