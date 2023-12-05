#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 miscModel->getTableAndStatus();
timeout=0
cid=1

- 输入表名，查看数据库表状态使用情况等，正常输出ok属性zt_account @ok
- 如被占用返回：1 client is using or hasn't closed the table properly（由于动态更新，此处断言两张表正常状态）属性zt_zoutput @ok
- 这里测试传入不正确的type属性zt_account @0

*/
global $tester;
$tester->loadModel('misc');

r($tester->misc->getTableAndStatus('check'))  && p('zt_account') && e('ok'); //输入表名，查看数据库表状态使用情况等，正常输出ok
r($tester->misc->getTableAndStatus('check'))  && p('zt_zoutput') && e('ok'); //如被占用返回：1 client is using or hasn't closed the table properly（由于动态更新，此处断言两张表正常状态）
r($tester->misc->getTableAndStatus('aaaaa'))  && p('zt_account') && e('0');  //这里测试传入不正确的type
?>
