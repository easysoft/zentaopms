#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) .'/lib/init.php';
include dirname(dirname(dirname(__FILE__))) .'/class/misc.class.php';

$misc = new Misc('admin');

r($misc->getTableAndStatus('check'))  && p('zt_account') && e('ok'); //输入表名，查看数据库表状态使用情况等，正常输出ok
r($misc->getTableAndStatus('check'))  && p('zt_zoutput') && e('ok'); //如被占用返回：1 client is using or hasn't closed the table properly（由于动态更新，此处断言两张表正常状态）
r($misc->getTableAndStatus('repair')) && p('zt_webhook') && e('ok'); //可以传入repair参数，状态与上述一致
r($misc->getTableAndStatus('aaaaa'))  && p('zt_account') && e('ok'); //这里测试传入不同参数||返回值相同
?>
