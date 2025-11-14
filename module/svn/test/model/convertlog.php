#!/usr/bin/env php
<?php

/**

title=测试 svnModel::convertLog();
timeout=0
cid=18713

- 执行svnTest模块的convertLogTest方法，参数是$normalLog 属性revision @e7699d04f1586d337f34496da932dde55db92616
- 执行svnTest模块的convertLogTest方法，参数是$emptyLog  @0
- 执行svnTest模块的convertLogTest方法，参数是$authorLog 属性author @testuser
- 执行svnTest模块的convertLogTest方法，参数是$dateLog 属性date @2023-01-03 12:30:45
- 执行svnTest模块的convertLogTest方法，参数是$msgLog 属性msg @Fix critical bug    Update documentation

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/svn.unittest.class.php';

su('admin');

$svnTest = new svnTest();

// 测试步骤1：正常完整日志转换
$normalLog = array();
$normalLog[] = "commit e7699d04f1586d337f34496da932dde55db92616";
$normalLog[] = "Author: zhengrunyu <zhengrunyu@easycorp.ltd>";
$normalLog[] = "Date:   Thu May 5 16:19:48 2022 +0800";
$normalLog[] = "    Fix bug 22061.";
r($svnTest->convertLogTest($normalLog)) && p('revision') && e('e7699d04f1586d337f34496da932dde55db92616');

// 测试步骤2：空日志数组处理
$emptyLog = array();
r($svnTest->convertLogTest($emptyLog)) && p() && e(0);

// 测试步骤3：验证作者字段解析（移除邮箱）
$authorLog = array();
$authorLog[] = "commit abc123";
$authorLog[] = "Author: testuser <test@example.com>";
$authorLog[] = "Date:   Mon Jan 1 00:00:00 2023 +0800";
r($svnTest->convertLogTest($authorLog)) && p('author') && e('testuser');

// 测试步骤4：验证日期格式转换
$dateLog = array();
$dateLog[] = "commit def456";
$dateLog[] = "Author: developer";
$dateLog[] = "Date:   Tue Jan 2 12:30:45 2023 +0800";
r($svnTest->convertLogTest($dateLog)) && p('date') && e('2023-01-03 12:30:45');

// 测试步骤5：验证提交信息解析
$msgLog = array();
$msgLog[] = "commit 789xyz";
$msgLog[] = "Author: admin";
$msgLog[] = "Date:   Wed Jan 3 09:15:30 2023 +0800";
$msgLog[] = "    Fix critical bug";
$msgLog[] = "    Update documentation";
r($svnTest->convertLogTest($msgLog)) && p('msg') && e('Fix critical bug    Update documentation');