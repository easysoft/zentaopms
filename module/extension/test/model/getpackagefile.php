#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::getPackageFile();
timeout=0
cid=16464

- 步骤1：正常插件代号 @/apps/zentao/tmp/extension/code1.zip
- 步骤2：空字符串 @/apps/zentao/tmp/extension/.zip
- 步骤3：特殊字符 @/apps/zentao/tmp/extension/test_special.zip
- 步骤4：路径分隔符 @/apps/zentao/tmp/extension/path/to/extension.zip
- 步骤5：普通用户权限 @/apps/zentao/tmp/extension/zentaopatch.zip
- 步骤6：超长插件代号 @/apps/zentao/tmp/extension/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.zip

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

// 用户登录
su('admin');

// 加载extension模型
global $tester;
$tester->loadModel('extension');

// 设置临时目录
$tester->extension->app->tmpRoot = '/apps/zentao/tmp/';

r($tester->extension->getPackageFile('code1')) && p() && e('/apps/zentao/tmp/extension/code1.zip'); // 步骤1：正常插件代号
r($tester->extension->getPackageFile('')) && p() && e('/apps/zentao/tmp/extension/.zip'); // 步骤2：空字符串
r($tester->extension->getPackageFile('test_special')) && p() && e('/apps/zentao/tmp/extension/test_special.zip'); // 步骤3：特殊字符
r($tester->extension->getPackageFile('path/to/extension')) && p() && e('/apps/zentao/tmp/extension/path/to/extension.zip'); // 步骤4：路径分隔符

// 切换普通用户测试
su('user');
r($tester->extension->getPackageFile('zentaopatch')) && p() && e('/apps/zentao/tmp/extension/zentaopatch.zip'); // 步骤5：普通用户权限

// 超长插件代号测试
$longExtensionName = str_repeat('a', 100);
r($tester->extension->getPackageFile($longExtensionName)) && p() && e('/apps/zentao/tmp/extension/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.zip'); // 步骤6：超长插件代号