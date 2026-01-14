#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::erasePackage();
timeout=0
cid=16452

- 步骤1：正常清除已安装插件包并验证返回数组 @0
- 步骤2：清除不存在的插件包验证处理结果 @0
- 步骤3：验证数据库记录是否被正确删除 @0
- 步骤4：测试包文件存在时的删除命令生成 @0
- 步骤5：测试解压目录存在时的删除命令生成 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('extension')->gen(10);

su('admin');

$extensionTest = new extensionModelTest();

r($extensionTest->erasePackageTest('code1')) && p() && e('0'); // 步骤1：正常清除已安装插件包并验证返回数组
r($extensionTest->erasePackageTest('nonexistent')) && p() && e('0'); // 步骤2：清除不存在的插件包验证处理结果
r($extensionTest->erasePackageTest('code2')) && p() && e('0'); // 步骤3：验证数据库记录是否被正确删除
r($extensionTest->erasePackageTest('code3')) && p() && e('0'); // 步骤4：测试包文件存在时的删除命令生成
r($extensionTest->erasePackageTest('code4')) && p() && e('0'); // 步骤5：测试解压目录存在时的删除命令生成