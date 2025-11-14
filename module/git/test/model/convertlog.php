#!/usr/bin/env php
<?php

/**

title=测试 gitModel::convertLog();
timeout=0
cid=16545

- 执行git模块的convertLog方法，参数是$log 属性revision @e7699d04f1586d337f34496da932dde55db92616
- 执行git模块的convertLog方法，参数是$log2 属性msg @  * First line of commit message  * Second line of commit message  * Third line for testing
- 执行git模块的convertLog方法，参数是$log3 属性revision @def456789012345678901234567890abcdef12
- 执行git模块的convertLog方法，参数是$log4 属性author @Simple User
- 执行git模块的convertLog方法，参数是$log5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester;
$git = $tester->loadModel('git');

// 测试步骤1：正常Git日志解析
$log = array();
$log[] = "commit e7699d04f1586d337f34496da932dde55db92616";
$log[] = "Author: zhengrunyu <zhenrunyu@easycorp.ltd>";
$log[] = "Date:   Thu May 5 16:19:48 2022 +0800";
$log[] = "  * Fix bug 22061.";
r($git->convertLog($log)) && p('revision') && e('e7699d04f1586d337f34496da932dde55db92616');

// 测试步骤2：多行注释的Git日志解析
$log2 = array();
$log2[] = "commit abc123def456789012345678901234567890abcd";
$log2[] = "Author: test user <test@example.com>";
$log2[] = "Date:   Fri Jan 1 12:00:00 2023 +0800";
$log2[] = "  * First line of commit message";
$log2[] = "  * Second line of commit message";
$log2[] = "  * Third line for testing";
r($git->convertLog($log2)) && p('msg') && e('  * First line of commit message  * Second line of commit message  * Third line for testing');

// 测试步骤3：包含文件变更的Git日志解析
$log3 = array();
$log3[] = "commit def456789012345678901234567890abcdef12";
$log3[] = "Author: developer <dev@test.com>";
$log3[] = "Date:   Sat Feb 1 10:30:00 2023 +0800";
$log3[] = "  * Update files";
$log3[] = "M\tmodule/git/model.php";
$log3[] = "A\tmodule/git/test/model/newfile.php";
$log3[] = "D\tmodule/git/old/oldfile.php";
r($git->convertLog($log3)) && p('revision') && e('def456789012345678901234567890abcdef12');

// 测试步骤4：复杂提交者信息的日志解析
$log4 = array();
$log4[] = "commit 1234567890abcdef1234567890abcdef12345678";
$log4[] = "Author: Simple User <user@test.com>";
$log4[] = "Date:   Sun Mar 1 14:15:16 2023 +0800";
$log4[] = "  * Testing author extraction";
r($git->convertLog($log4)) && p('author') && e('Simple User');

// 测试步骤5：空日志数组处理
$log5 = array();
r($git->convertLog($log5)) && p() && e('0');