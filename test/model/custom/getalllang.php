#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->getAllLang();
cid=1
pid=1

测试正常查询 >> 2

*/

$custom = new customTest();

r($custom->getAllLangTest()) && p() && e('2');  //测试正常查询