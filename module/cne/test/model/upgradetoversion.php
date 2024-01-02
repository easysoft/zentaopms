#!/usr/bin/env php
<?php

/**

title=测试 cneModel->upgradeToVersion();
timeout=0
cid=1

- 升级的版本为空 @1
- 升级的版本正确 @1
- 升级的版本不正确 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel = new cneTest();

r($cneModel->upgradeToVersionTest(1, ''))             && p() && e('1');  // 升级的版本为空
r($cneModel->upgradeToVersionTest(2, '2023.12.1201')) && p() && e('1');  // 升级的版本正确
r($cneModel->upgradeToVersionTest(2, '2023.12'))      && p() && e('0');  // 升级的版本不正确