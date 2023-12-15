#!/usr/bin/env php
<?php

/**

title=测试 mrModel->getPairs();
timeout=0
cid=1

- 代码库ID正确属性1 @Test MR
- 代码库ID为空 @0
- 代码库ID不存在 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('mr')->gen(1);
su('admin');

global $tester;
$mrTester = $tester->loadModel('mr');

r($mrTester->getPairs(1))   && p('1') && e('Test MR'); // 代码库ID正确
r($mrTester->getPairs(0))   && p()    && e('0');       // 代码库ID为空
r($mrTester->getPairs(100)) && p()    && e('0');       // 代码库ID不存在