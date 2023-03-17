#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/git.class.php';
su('admin');

/**

title=测试gitModel->convertLog();
cid=1
pid=1

使用正确格式的数据 >> e7699d04f1586d337f34496da932dde55db92616

*/

$git = new gitTest();

$log[] = "commit e7699d04f1586d337f34496da932dde55db92616";
$log[] = "Author: zhengrunyu <zhenrunyu@easycorp.ltd>";
$log[] = "Date:   Thu May 5 16:19:48 2022 +0800";
$log[] = "  * Fix bug 22061.";
r($git->convertLog($log)) && p('revision') && e('e7699d04f1586d337f34496da932dde55db92616');    // 使用正确格式的数据

$log = array();
r($git->convertLog($log)) && p('revision') && e(0);     // 使用空的数据

