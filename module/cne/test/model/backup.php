#!/usr/bin/env php
<?php

/**

title=测试 cneModel::backup();
timeout=0
cid=15603

- 测试步骤1:正常备份请求，实例ID=1，默认账号属性code @200
- 测试步骤2:备份请求，实例ID=2，指定账号testuser第data条的account属性 @testuser
- 测试步骤3:备份请求，实例ID=3，指定mode=manual第data条的mode属性 @manual
- 测试步骤4:备份请求，实例ID=4，指定mode=system第data条的mode属性 @system
- 测试步骤5:备份请求，实例ID=5，指定mode=upgrade第data条的mode属性 @upgrade

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->backupTest(1, null, '')) && p('code') && e('200'); // 测试步骤1:正常备份请求，实例ID=1，默认账号
r($cneTest->backupTest(2, 'testuser', '')) && p('data:account') && e('testuser'); // 测试步骤2:备份请求，实例ID=2，指定账号testuser
r($cneTest->backupTest(3, null, 'manual')) && p('data:mode') && e('manual'); // 测试步骤3:备份请求，实例ID=3，指定mode=manual
r($cneTest->backupTest(4, 'admin', 'system')) && p('data:mode') && e('system'); // 测试步骤4:备份请求，实例ID=4，指定mode=system
r($cneTest->backupTest(5, 'admin', 'upgrade')) && p('data:mode') && e('upgrade'); // 测试步骤5:备份请求，实例ID=5，指定mode=upgrade