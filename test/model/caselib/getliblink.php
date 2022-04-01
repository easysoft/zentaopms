#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->getLibLink();
cid=1
pid=1

*/

$caselib = new caselibTest();
$link1   = $caselib->getLibLinkTest('caselib', 'create', '');
$link2   = $caselib->getLibLinkTest('caselib', 'test', '');
$link3   = $caselib->getLibLinkTest('tree', '', '');
$link4   = $caselib->getLibLinkTest('', '', '');

r($link1) && p() && e('model/caselib/getliblink.php?m=caselib&f=browse&t=&libID=%s');                       // 测试参数情况1返回值
r($link2) && p() && e('model/caselib/getliblink.php?m=caselib&f=test&t=&libID=%s');                         // 测试参数情况2返回值
r($link3) && p() && e('model/caselib/getliblink.php?m=tree&f=&t=&libID=%s&type=caselib&currentModuleID=0'); // 测试参数情况3返回值
r($link4) && p() && e('model/caselib/getliblink.php?m=caselib&f=browse&t=&libID=%s');                       // 测试参数情况4返回值
