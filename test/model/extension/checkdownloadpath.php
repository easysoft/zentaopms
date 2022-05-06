#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 extensionModel->checkDownloadPath();
cid=1
pid=1

*/

global $tester;
$result = $tester->loadModel('extension')->checkDownloadPath();

r(count($result)) && p()         && e('2');  // 查看获取到的结果数量
r($result)        && p('result') && e('ok'); // 查看获取到的结果
