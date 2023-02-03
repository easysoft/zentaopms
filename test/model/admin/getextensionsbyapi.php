#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 adminModel::getExtensionsByAPI();
cid=1
pid=1

查询断网情况插件数量 >> 3

*/

global $tester;
$tester->loadModel('admin');
$result = $tester->admin->getExtensionsByAPI('extension', 6, false);

r(count($result)) && p() && e('3'); // 查询断网情况插件数量
