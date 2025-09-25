#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getBackupStatus();
timeout=0
cid=0

- 步骤1：正常情况，返回对象属性code @200
- 步骤2：不存在的实例ID属性code @404
- 步骤3：空的备份名称属性code @200
- 步骤4：特殊字符的备份名称属性code @200
- 步骤5：无效的实例ID属性message @Instance not found

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 创建测试实例，不依赖数据库
$cneTest = new cneTest();

// 测试步骤
r($cneTest->getBackupStatusTest(1, 'backup-20241207-001')) && p('code') && e('200'); // 步骤1：正常情况，返回对象
r($cneTest->getBackupStatusTest(999, 'backup-20241207-001')) && p('code') && e('404'); // 步骤2：不存在的实例ID
r($cneTest->getBackupStatusTest(1, '')) && p('code') && e('200'); // 步骤3：空的备份名称
r($cneTest->getBackupStatusTest(1, 'backup-test@#$%')) && p('code') && e('200'); // 步骤4：特殊字符的备份名称
r($cneTest->getBackupStatusTest(0, 'backup-20241207-001')) && p('message') && e('Instance not found'); // 步骤5：无效的实例ID