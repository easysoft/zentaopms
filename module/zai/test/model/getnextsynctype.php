#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getNextSyncType();
timeout=0
cid=19771

- 测试无参数时获取第一个同步类型 @story
- 测试从story获取下一个同步类型 @demand
- 测试从bug获取下一个同步类型 @doc
- 测试从doc获取下一个同步类型 @design
- 测试从最后一个类型获取下一个 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);

su('admin');

global $tester;
$zai = new zaiModelTest();
$tester->config->edition = 'ipd';

/* 测试无参数时获取第一个同步类型 */
r($zai->getNextSyncTypeTest()) && p() && e('story'); // 测试无参数时获取第一个同步类型

/* 测试从story获取下一个同步类型 */
r($zai->getNextSyncTypeTest('story')) && p() && e('demand'); // 测试从story获取下一个同步类型

/* 测试从bug获取下一个同步类型 */
r($zai->getNextSyncTypeTest('bug')) && p() && e('doc'); // 测试从bug获取下一个同步类型

/* 测试从doc获取下一个同步类型 */
r($zai->getNextSyncTypeTest('doc')) && p() && e('design'); // 测试从doc获取下一个同步类型

/* 测试从feedback获取下一个同步类型 */
r($zai->getNextSyncTypeTest('feedback')) && p() && e('0'); // 测试从最后一个类型获取下一个
