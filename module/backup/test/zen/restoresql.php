#!/usr/bin/env php
<?php

/**

title=测试 backupZen::restoreSQL();
timeout=0
cid=0

- 测试不存在的备份文件属性result @success
- 测试普通SQL备份文件还原属性result @success
- 测试带php扩展的SQL备份文件还原属性result @success
- 测试空文件名属性result @success
- 测试特殊命名的SQL备份文件属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$backupTest = new backupZenTest();

r($backupTest->restoreSQLTest('test_nonexist')) && p('result') && e('success'); // 测试不存在的备份文件
r($backupTest->restoreSQLTest('test_normal')) && p('result') && e('success'); // 测试普通SQL备份文件还原
r($backupTest->restoreSQLTest('test_withphp')) && p('result') && e('success'); // 测试带php扩展的SQL备份文件还原
r($backupTest->restoreSQLTest('')) && p('result') && e('success'); // 测试空文件名
r($backupTest->restoreSQLTest('test_special_123')) && p('result') && e('success'); // 测试特殊命名的SQL备份文件