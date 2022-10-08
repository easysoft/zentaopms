#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/admin.class.php';
su('admin');

/**

title=测试 adminModel->getRegisterInfo();
cid=1
pid=1

正常测试 >> 易软天创网络科技有限公司

*/

$admin = new adminTest();

r($admin->getRegisterInfoTest()) && p('company') && e('易软天创网络科技有限公司'); //正常测试