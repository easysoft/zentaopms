#!/usr/bin/env php
<?php
/**

title=测试 hostModel->getList();
timeout=0
cid=1

- 测试默认参数是否为获取全部主机。 @30
- 测试获取全部主机的数量。 @30
- 测试根据模块获取主机的数量。 @3
- 测试根据查询条件获取主机的数量。 @15
- 测试查询结果中主机数据是否正确。
 - 第0条的id属性 @30
 - 第0条的name属性 @主机30
 - 第0条的type属性 @normal
 - 第0条的hostType属性 @virtual

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('account')->gen(100);
zdTable('serverroom')->gen(100);
zdTable('module')->config('module')->gen(100)->fixPath();
zdTable('host')->config('host')->gen(30);
zdTable('lang')->gen(0);
su('admin');

global $tester;
$tester->loadModel('host');
$_SESSION['hostQuery'] = "hostType='physical'";

r(count($tester->host->getList()))                && p() && e(30); // 测试默认参数是否为获取全部主机。
r(count($tester->host->getList('all')))           && p() && e(30); // 测试获取全部主机的数量。
r(count($tester->host->getList('bymodule', '1'))) && p() && e(3);  // 测试根据模块获取主机的数量。
r(count($tester->host->getList('bysearch')))      && p() && e(15); // 测试根据查询条件获取主机的数量。

r($tester->host->getList('all')) && p('0:id,name,type,hostType') && e('30,主机30,normal,virtual'); // 测试查询结果中主机数据是否正确。
