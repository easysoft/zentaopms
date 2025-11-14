#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 miscModel->getPluginRemind();
timeout=0
cid=17212

- 调用方法返回值 @0
- 调用方法返回值 @0
- 调用方法返回值 @0
- 调用方法返回值 @0
- 调用方法返回值 @0

*/

zenData('extension')->gen(10);

global $tester;
$tester->loadModel('misc');

/* 由于没办法上传过期授权文件，这个单测无法实际测试。 */
r($tester->misc->getPluginRemind()) && p() && e('0'); //调用方法返回值
r($tester->misc->getPluginRemind()) && p() && e('0'); //调用方法返回值
r($tester->misc->getPluginRemind()) && p() && e('0'); //调用方法返回值
r($tester->misc->getPluginRemind()) && p() && e('0'); //调用方法返回值
r($tester->misc->getPluginRemind()) && p() && e('0'); //调用方法返回值
?>
