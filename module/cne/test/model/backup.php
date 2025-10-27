#!/usr/bin/env php
<?php

/**

title=测试 cneModel::backup();
timeout=0
cid=0

- 步骤1：正常实例备份，使用默认用户账号属性code @200
- 步骤2：正常实例备份，指定用户账号属性code @200
- 步骤3：正常实例备份，指定备份模式manual属性code @200
- 步骤4：正常实例备份，指定备份模式system属性code @200
- 步骤5：正常实例备份，指定备份模式upgrade属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 创建测试实例
$cneTest = new cneTest();

// 测试步骤
r($cneTest->backupTest(1)) && p('code') && e('200'); // 步骤1：正常实例备份，使用默认用户账号
r($cneTest->backupTest(1, 'testuser')) && p('code') && e('200'); // 步骤2：正常实例备份，指定用户账号
r($cneTest->backupTest(1, null, 'manual')) && p('code') && e('200'); // 步骤3：正常实例备份，指定备份模式manual
r($cneTest->backupTest(2, 'admin', 'system')) && p('code') && e('200'); // 步骤4：正常实例备份，指定备份模式system
r($cneTest->backupTest(2, null, 'upgrade')) && p('code') && e('200'); // 步骤5：正常实例备份，指定备份模式upgrade