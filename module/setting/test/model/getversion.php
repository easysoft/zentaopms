#!/usr/bin/env php
<?php

/**

title=测试 settingModel::getVersion();
timeout=0
cid=18364

- 步骤1：无版本配置时返回默认版本 @0.3.beta
- 步骤2：正常版本号配置 @10.0
- 步骤3：3.0.stable特殊转换 @3.0
- 步骤4：其他稳定版本号 @8.5
- 步骤5：空字符串版本配置 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/setting.unittest.class.php';

zenData('config')->gen(0);

su('admin');

$setting = new settingTest();

r($setting->getVersionTest()) && p() && e('0.3.beta');           // 步骤1：无版本配置时返回默认版本
r($setting->getVersionTest('10.0')) && p() && e('10.0');        // 步骤2：正常版本号配置
r($setting->getVersionTest('3.0.stable')) && p() && e('3.0');   // 步骤3：3.0.stable特殊转换
r($setting->getVersionTest('8.5')) && p() && e('8.5');          // 步骤4：其他稳定版本号
r($setting->getVersionTest('')) && p() && e('0');              // 步骤5：空字符串版本配置