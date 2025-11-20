#!/usr/bin/env php
<?php

/**

title=开源版m=user&f=reset测试
timeout=0
cid=1

- reset页面信息提示测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @reset页面信息提示测试通过
- reset页面重置密码测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @reset页面重置密码测试成功

*/
include dirname(__FILE__, 2) . '/lib/ui/reset.ui.class.php';

$tester = new resetTester();
r($tester->verifyResetPageMessage()) && p('status,message') && e('SUCCESS,reset页面信息提示测试通过'); // reset页面信息提示测试
r($tester->verifyAdminResetSubmit()) && p('status,message') && e('SUCCESS,reset页面重置密码测试成功'); // reset页面重置密码测试
$tester->closeBrowser();