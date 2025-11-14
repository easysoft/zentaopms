#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getBackupList();
timeout=0
cid=15614

- 测试步骤1:正常获取备份列表,实例ID=1属性code @200
- 测试步骤2:获取备份列表,实例ID=2属性code @200
- 测试步骤3:获取备份列表,实例ID=3属性code @200
- 测试步骤4:获取备份列表,无效实例ID=0属性code @404
- 测试步骤5:获取备份列表,不存在实例ID=999属性code @404
- 测试步骤6:获取备份列表,负数实例ID=-1属性code @404
- 测试步骤7:正常获取备份列表,实例ID=1,检查data属性为数组 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->getBackupListTest(1)) && p('code') && e('200'); // 测试步骤1:正常获取备份列表,实例ID=1
r($cneTest->getBackupListTest(2)) && p('code') && e('200'); // 测试步骤2:获取备份列表,实例ID=2
r($cneTest->getBackupListTest(3)) && p('code') && e('200'); // 测试步骤3:获取备份列表,实例ID=3
r($cneTest->getBackupListTest(0)) && p('code') && e('404'); // 测试步骤4:获取备份列表,无效实例ID=0
r($cneTest->getBackupListTest(999)) && p('code') && e('404'); // 测试步骤5:获取备份列表,不存在实例ID=999
r($cneTest->getBackupListTest(-1)) && p('code') && e('404'); // 测试步骤6:获取备份列表,负数实例ID=-1
r(is_array($cneTest->getBackupListTest(1)->data)) && p() && e('1'); // 测试步骤7:正常获取备份列表,实例ID=1,检查data属性为数组