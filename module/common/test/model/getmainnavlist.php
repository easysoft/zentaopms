#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';
su('admin');

/**

title=测试 commonModel::getMainNavList();
timeout=0
cid=0

- 步骤1：正常情况 - 传入有效模块名 @~~
- 步骤2：使用默认菜单配置 @~~
- 步骤3：空模块名 @~~
- 步骤4：不存在的模块名 @~~
- 步骤5：权限验证 - 检查返回结果非空 @~~

*/

$commonTest = new commonTest();

r($commonTest->getMainNavListTest('product')) && p() && e('~~'); // 步骤1：正常情况 - 传入有效模块名
r($commonTest->getMainNavListTest('product', true)) && p() && e('~~'); // 步骤2：使用默认菜单配置
r($commonTest->getMainNavListTest('')) && p() && e('~~'); // 步骤3：空模块名
r($commonTest->getMainNavListTest('nonexistent')) && p() && e('~~'); // 步骤4：不存在的模块名
r($commonTest->getMainNavListTest('my')) && p() && e('~~'); // 步骤5：权限验证 - 检查返回结果