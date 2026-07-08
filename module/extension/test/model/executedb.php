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
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('extension')->gen(10);

su('admin');

$extensionTest  = new extensionModelTest();
$extensionModel = $extensionTest->instance;

function removeTestDir(string $dir): void
{
    if(!is_dir($dir)) return;

    $files = array_diff(scandir($dir), array('.', '..'));
    foreach($files as $file)
    {
        $path = $dir . DS . $file;
        is_dir($path) ? removeTestDir($path) : unlink($path);
    }

    rmdir($dir);
}

// 准备测试插件 SQL 文件。
$pkgRoot = sys_get_temp_dir() . DS . 'extension_pkg_' . uniqid() . DS;
$code1DB = $pkgRoot . 'code1' . DS . 'db' . DS;
$code2DB = $pkgRoot . 'code2' . DS . 'db' . DS;
mkdir($code1DB, 0777, true);
mkdir($code2DB, 0777, true);

file_put_contents($code1DB . 'install.sql', "CREATE TABLE IF NOT EXISTS `zt_ext_execute_install` (`id` int(11) NOT NULL);\n");
file_put_contents($code1DB . 'uninstall.sql', "DROP TABLE IF EXISTS `zt_ext_execute_install`;\n");
file_put_contents($code2DB . 'install.sql', "THIS IS INVALID SQL;\n");

$originalPkgRoot = $extensionModel->pkgRoot;
$extensionModel->pkgRoot = $pkgRoot;

r($extensionTest->executeDBTest('code1', 'install'))   && p('result') && e('ok');   // 步骤1：执行有效扩展的安装SQL
r($extensionTest->executeDBTest('code1', 'uninstall')) && p('result') && e('ok');   // 步骤2：执行有效扩展的卸载SQL
r($extensionTest->executeDBTest('nonexistent'))        && p('result') && e('ok');   // 步骤3：不存在SQL文件的扩展
r($extensionTest->executeDBTest('code1'))              && p('result') && e('ok');   // 步骤4：默认参数调用（install）
r($extensionTest->executeDBTest('code2', 'install'))   && p('result') && e('fail'); // 步骤5：包含无效SQL的扩展

// 恢复原始pkgRoot并清理测试数据。
$extensionModel->pkgRoot = $originalPkgRoot;
removeTestDir($pkgRoot);
