#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->unlinkBug();
cid=1
pid=1

项目版本解除bug >> ,311,11
执行版本解除bug >> ,301,101

*/

$buildIDList = array('1', '11');
$bugs        = array('301', '311');

$build = new buildTest();

r($build->unlinkBugTest($buildIDList[0],$bugs,$bugs[0])) && p('1:bugs,project')    && e(',311,11');               //项目版本解除bug
r($build->unlinkBugTest($buildIDList[1],$bugs,$bugs[1])) && p('11:bugs,execution') && e(',301,101');              //执行版本解除bug

