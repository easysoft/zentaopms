#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->getTarget();
timeout=0
cid=19803

- 测试不存在的类型 @0
- 测试精确 ID 命中 @1
- 测试精确 ID 不存在 @0
- 测试Story精确 ID 命中 @1
- 测试边界ID 0 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('bug')->gen(1);
zenData('story')->gen(1);
su('admin');

$zai = new zaiModelTest();

r($zai->getTargetTest('invalidtype', 1)) && p() && e('0'); // 测试不存在的类型
r($zai->getTargetTest('bug', 1)) && p('id') && e('1'); // 测试精确 ID 命中
r($zai->getTargetTest('bug', 99)) && p() && e('0'); // 测试精确 ID 不存在
r($zai->getTargetTest('story', 1)) && p('id') && e('1'); // 测试Story精确 ID 命中
r($zai->getTargetTest('story', 0)) && p() && e('0'); // 测试边界ID 0
