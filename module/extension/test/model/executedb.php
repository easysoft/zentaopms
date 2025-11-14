#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::executeDB();
timeout=0
cid=16453

- 步骤1：执行有效扩展的安装SQL属性result @ok
- 步骤2：执行有效扩展的卸载SQL属性result @ok
- 步骤3：不存在SQL文件的扩展属性result @ok
- 步骤4：默认参数调用（install）属性result @ok
- 步骤5：包含无效SQL的扩展属性result @fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

zenData('extension')->gen(10);

su('admin');

$extensionTest = new extensionTest();

// 设置临时的pkgRoot用于测试
global $tester;
$originalPkgRoot = $tester->extension->pkgRoot;
$tester->extension->pkgRoot = '/tmp/pkg/';

r($extensionTest->executeDBTest('code1', 'install'))   && p('result') && e('ok');   // 步骤1：执行有效扩展的安装SQL
r($extensionTest->executeDBTest('code1', 'uninstall')) && p('result') && e('ok');   // 步骤2：执行有效扩展的卸载SQL
r($extensionTest->executeDBTest('nonexistent'))       && p('result') && e('ok');   // 步骤3：不存在SQL文件的扩展
r($extensionTest->executeDBTest('code1'))             && p('result') && e('ok');   // 步骤4：默认参数调用（install）
r($extensionTest->executeDBTest('code2', 'install'))   && p('result') && e('fail'); // 步骤5：包含无效SQL的扩展

// 恢复原始pkgRoot
$tester->extension->pkgRoot = $originalPkgRoot;