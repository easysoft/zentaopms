#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printClientLink();
timeout=0
cid=15690

- 步骤1：两个配置都启用 @1
- 步骤2：仅xxserver安装 @0
- 步骤3：仅xuanxuan开启 @0
- 步骤4：两个都禁用 @0
- 步骤5：xxserver未设置 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

r($commonTest->printClientLinkTest('both_enabled')) && p() && e('1');     // 步骤1：两个配置都启用
r($commonTest->printClientLinkTest('xxserver_only')) && p() && e('0');    // 步骤2：仅xxserver安装
r($commonTest->printClientLinkTest('xuanxuan_only')) && p() && e('0');    // 步骤3：仅xuanxuan开启
r($commonTest->printClientLinkTest('both_disabled')) && p() && e('0');    // 步骤4：两个都禁用
r($commonTest->printClientLinkTest('xxserver_not_set')) && p() && e('0'); // 步骤5：xxserver未设置