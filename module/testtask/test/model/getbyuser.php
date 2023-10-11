#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->config('product')->gen(2);
zdTable('project')->config('project')->gen(5);
zdTable('build')->config('build')->gen(1);
zdTable('testtask')->config('testtask')->gen(24);

/**

title=测试 testtaskModel->getByUser();
cid=1
pid=1

*/

global $tester, $app;

$app->user->view->sprints = implode(',', range(1, 10));
$app->setModuleName('my');
$app->setMethodName('testtask');
$app->loadClass('pager', true);
$pager = new pager(0, 5, 1);

$testtask = $tester->loadModel('testtask');

r($testtask->getByUser(''))      && p() && e(0);
r($testtask->getByUser('admin')) && p() && e(0);

$tasks = $testtask->getByUser('user3');
r(count($tasks)) && p() && e(5);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,8,测试单8,user3,这是测试单描述8,doing,no'); // 获取 ID 为 8 的测试单的详细信息。
r($tasks) && p('1:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,7,测试单7,user3,这是测试单描述7,doing,no'); // 获取 ID 为 7 的测试单的详细信息。
r($tasks) && p('2:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,6,测试单6,user3,这是测试单描述6,doing,no'); // 获取 ID 为 6 的测试单的详细信息。

$tasks = $testtask->getByUser('user4');
r(count($tasks)) && p() && e(10);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('1:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('2:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager);
r(count($tasks)) && p() && e(5);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('1:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('2:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_desc');
r(count($tasks)) && p() && e(5);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('1:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('2:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_asc');
r(count($tasks)) && p() && e(5);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,12,测试单12,user4,这是测试单描述12,wait,no');  // 获取 ID 为 12 的测试单的详细信息。
r($tasks) && p('1:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,2,执行1,1,1,项目1版本1,13,测试单13,user4,这是测试单描述13,doing,no'); // 获取 ID 为 13 的测试单的详细信息。
r($tasks) && p('2:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,14,测试单14,user4,这是测试单描述14,doing,no'); // 获取 ID 为 14 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_desc');
r(count($tasks)) && p() && e(10);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('1:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('2:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_asc');
r(count($tasks)) && p() && e(10);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,12,测试单12,user4,这是测试单描述12,wait,no');  // 获取 ID 为 12 的测试单的详细信息。
r($tasks) && p('1:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,2,执行1,1,1,项目1版本1,13,测试单13,user4,这是测试单描述13,doing,no'); // 获取 ID 为 13 的测试单的详细信息。
r($tasks) && p('2:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,14,测试单14,user4,这是测试单描述14,doing,no'); // 获取 ID 为 14 的测试单的详细信息。
r($tasks) && p('3:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,15,测试单15,user4,这是测试单描述15,doing,no'); // 获取 ID 为 15 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_desc', 'wait');
r(count($tasks)) && p() && e(9);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('1:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('2:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_asc', 'wait');
r(count($tasks)) && p() && e(9);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,12,测试单12,user4,这是测试单描述12,wait,no');  // 获取 ID 为 12 的测试单的详细信息。
r($tasks) && p('1:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,2,执行1,1,1,项目1版本1,13,测试单13,user4,这是测试单描述13,doing,no'); // 获取 ID 为 13 的测试单的详细信息。
r($tasks) && p('2:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,14,测试单14,user4,这是测试单描述14,doing,no'); // 获取 ID 为 14 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_desc', 'done');
r(count($tasks)) && p() && e(1);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,20,测试单20,user4,这是测试单描述20,done,no');  // 获取 ID 为 20 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_asc', 'done');
r(count($tasks)) && p() && e(1);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,20,测试单20,user4,这是测试单描述20,done,no');  // 获取 ID 为 20 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_desc', 'wait');
r(count($tasks)) && p() && e(5);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('1:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('2:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_asc', 'wait');
r(count($tasks)) && p() && e(5);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,12,测试单12,user4,这是测试单描述12,wait,no');  // 获取 ID 为 12 的测试单的详细信息。
r($tasks) && p('1:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,2,执行1,1,1,项目1版本1,13,测试单13,user4,这是测试单描述13,doing,no'); // 获取 ID 为 13 的测试单的详细信息。
r($tasks) && p('2:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,14,测试单14,user4,这是测试单描述14,doing,no'); // 获取 ID 为 14 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_desc', 'done');
r(count($tasks)) && p() && e(1);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,20,测试单20,user4,这是测试单描述20,done,no');  // 获取 ID 为 20 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_asc', 'done');
r(count($tasks)) && p() && e(1);
r($tasks) && p('0:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,20,测试单20,user4,这是测试单描述20,done,no');  // 获取 ID 为 20 的测试单的详细信息。
