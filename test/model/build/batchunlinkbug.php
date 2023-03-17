#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->batchUnlinkBug();
cid=1
pid=1

批量解除项目版本bug >> ,11
批量解除执行版本bug >> ,101

*/

$buildIDList = array('1', '11');
$bugs        = array('301', '311');

$build = new buildTest();

r($build->batchUnlinkBugTest($buildIDList[0],$bugs)) && p('1:bugs,project')    && e(',11');//批量解除项目版本bug
r($build->batchUnlinkBugTest($buildIDList[1],$bugs)) && p('11:bugs,execution') && e(',101');//批量解除执行版本bug

