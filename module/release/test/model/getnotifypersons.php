#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->getNotifyPersons();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

$release = zdTable('release')->config('release');
$release->stories->range('`1,2,3`,[]');
$release->bugs->range('`1,2,3`,[]');
$release->notify->range('`PO,QD,feedback`,`SC,ET,PT,CT`');
$release->status->range('normal,terminate');
$release->deleted->range('0{2},1');
$release->gen(5);

zdTable('story')->config('story')->gen(5);
zdTable('bug')->config('bug')->gen(5);
zdTable('product')->config('product')->gen(5);
zdTable('build')->config('build')->gen(5);
zdTable('team')->gen(0);
zdTable('user')->gen(5);
su('admin');


$releases = array(1, 2, 3);

$releaseTester = new releaseTest();
r($releaseTester->getNotifyPersonsTest($releases[0])) && p('admin') && e('admin'); // 测试获取状态为正常的发布的通知人员
r($releaseTester->getNotifyPersonsTest($releases[1])) && p()        && e('0');     // 测试获取状态为停止维护的发布的通知人员
r($releaseTester->getNotifyPersonsTest($releases[2])) && p('admin') && e('admin'); // 测试获取已删除的发布的通知人员
