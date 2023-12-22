#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试svnModel->convertLog();
timeout=0
cid=1

- 使用正确格式的数据属性revision @e7699d04f1586d337f34496da932dde55db92616
- 使用空的数据属性revision @0

*/

global $tester;
$svn = $tester->loadModel('svn');

$log = array();
$log[] = "commit e7699d04f1586d337f34496da932dde55db92616";
$log[] = "Author: zhengrunyu <zhenrunyu@easycorp.ltd>";
$log[] = "Date:   Thu May 5 16:19:48 2022 +0800";
$log[] = "  * Fix bug 22061.";
r($svn->convertLog($log)) && p('revision') && e('e7699d04f1586d337f34496da932dde55db92616');    // 使用正确格式的数据

$log = array();
r($svn->convertLog($log)) && p('revision') && e(0);     // 使用空的数据
