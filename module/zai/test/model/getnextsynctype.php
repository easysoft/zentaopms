#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getNextSyncType();
cid=0

- 测试无参数时获取第一个同步类型 >> 期望返回story
- 测试从story获取下一个同步类型 >> 期望返回bug
- 测试从bug获取下一个同步类型 >> 期望返回doc
- 测试从doc获取下一个同步类型 >> 期望返回design
- 测试从最后一个类型获取下一个 >> 期望返回null

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('config')->gen(0);

su('admin');

global $tester;
$zai = new zaiTest();

/* 测试无参数时获取第一个同步类型 */
r($zai->getNextSyncTypeTest()) && p() && e('story'); // 测试无参数时获取第一个同步类型

/* 测试从story获取下一个同步类型 */
r($zai->getNextSyncTypeTest('story')) && p() && e('bug'); // 测试从story获取下一个同步类型

/* 测试从bug获取下一个同步类型 */
r($zai->getNextSyncTypeTest('bug')) && p() && e('doc'); // 测试从bug获取下一个同步类型

/* 测试从doc获取下一个同步类型 */
r($zai->getNextSyncTypeTest('doc')) && p() && e('design'); // 测试从doc获取下一个同步类型

/* 测试从design获取下一个同步类型 */
r($zai->getNextSyncTypeTest('design')) && p() && e('0'); // 测试从最后一个类型获取下一个
