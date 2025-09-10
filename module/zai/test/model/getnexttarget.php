#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('story')->gen(1);
zenData('bug')->gen(1);

su('admin');

/**

title=测试 zaiModel::getNextTarget();
cid=0

- 测试不存在的同步类型 >> 期望返回null
- 测试story类型数据为空时 >> 期望返回null
- 测试bug类型数据不为空时 >> 期望返回ID1

*/

global $tester;
$zai = new zaiTest();

/* 测试不存在的同步类型 */
r($zai->getNextTargetTest('invalidtype', 1)) && p() && e('0'); // 测试不存在的同步类型

/* 测试story类型数据为空时 */
r($zai->getNextTargetTest('story', 2)) && p() && e('0'); // 测试story类型从ID 1开始获取

/* 测试bug类型数据不为空时 */
r($zai->getNextTargetTest('bug', 1)) && p('id') && e('1'); // 测试bug类型数据不为空时
