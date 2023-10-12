#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('case')->config('case')->gen(10);
zdTable('testrun')->config('testrun')->gen(10);

/**

title=测试 testtaskModel->getLinkableCasesByTestTask();
cid=1
pid=1

*/

global $tester, $app;

$app->setModuleName('testtask');
$app->setMethodName('linCase');
$app->loadClass('pager', true);
$pager = new pager(0, 5, 1);

$testtask = $tester->loadModel('testtask');

r($testtask->getLinkableCasesByTestTask(0)) && p() && e(0); // 测试单 0 可关联的用例数为 0。
r($testtask->getLinkableCasesByTestTask(2)) && p() && e(0); // 测试单 2 可关联的用例数为 0。

$tasks = $testtask->getLinkableCasesByTestTask(1);
r(count($tasks)) && p() && e(7); // 测试单 1 可关联的用例数为 7。
r($tasks) && p('0:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('2,这个是测试用例2,2,performance,no,normal,user1,fail');  // 查看测试单 1 可关联的用例 2 的详细信息。
r($tasks) && p('1:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($tasks) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。

$tasks = $testtask->getLinkableCasesByTestTask(1, array(1,2,3,4,5,6,7,8,9,10));
r(count($tasks)) && p() && e(0); // 测试单 1 排除用例 1-10 后可关联的用例数为 0。

$tasks = $testtask->getLinkableCasesByTestTask(1, array(2));
r(count($tasks)) && p() && e(6); // 测试单 1 排除用例 2 后可关联的用例数为 6。
r($tasks) && p('0:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($tasks) && p('1:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。
r($tasks) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。

$tasks = $testtask->getLinkableCasesByTestTask(1, array(2), 't1.id < 10');
r(count($tasks)) && p() && e(5); // 测试单 1 排除用例 2 并查询 id < 10 的用例后可关联的用例数为 5。
r($tasks) && p('0:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($tasks) && p('1:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。
r($tasks) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。

$tasks = $testtask->getLinkableCasesByTestTask(1, array(2), '', $pager);
r(count($tasks)) && p() && e(5); // 测试单 1 排除用例 2 并限制每页查询 5 条后可关联的用例数为 5。
r($tasks) && p('0:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($tasks) && p('1:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。
r($tasks) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。

$tasks = $testtask->getLinkableCasesByTestTask(1, array(), 't1.id > 10');
r(count($tasks)) && p() && e(0); // 测试单 1 查询 id > 10 的用例后可关联的用例数为 0。

$tasks = $testtask->getLinkableCasesByTestTask(1, array(), 't1.id < 10');
r(count($tasks)) && p() && e(6); // 测试单 1 查询 id < 10 的用例后可关联的用例数为 6。
r($tasks) && p('0:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('2,这个是测试用例2,2,performance,no,normal,user1,fail');  // 查看测试单 1 可关联的用例 2 的详细信息。
r($tasks) && p('1:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($tasks) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。

$tasks = $testtask->getLinkableCasesByTestTask(1, array(), 't1.id < 10', $pager);
r(count($tasks)) && p() && e(5); // 测试单 1 查询 id < 10 并限制每页查询 5 条的用例后可关联的用例数为 5。
r($tasks) && p('0:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('2,这个是测试用例2,2,performance,no,normal,user1,fail');  // 查看测试单 1 可关联的用例 2 的详细信息。
r($tasks) && p('1:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($tasks) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。

$tasks = $testtask->getLinkableCasesByTestTask(1, array(), '', $pager);
r(count($tasks)) && p() && e(5); // 测试单 1 限制每页查询 5 条后可关联的用例数为 5。
r($tasks) && p('0:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('2,这个是测试用例2,2,performance,no,normal,user1,fail');  // 查看测试单 1 可关联的用例 2 的详细信息。
r($tasks) && p('1:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($tasks) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。

$tasks = $testtask->getLinkableCasesByTestTask(1, array(2), 't1.id < 10', $pager);
r(count($tasks)) && p() && e(5); // 测试单 1 排除用例 2、查询 id < 10 的用例并限制每页查询 5 条后可关联的用例数为 5。
r($tasks) && p('0:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($tasks) && p('1:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。
r($tasks) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。

$tasks = $testtask->getLinkableCasesByTestTask(1, array(2,3,4), 't1.id < 10', $pager);
r(count($tasks)) && p() && e(3); // 测试单 1 排除用例 2-4、查询 id < 10 的用例并限制每页查询 5 条后可关联的用例数为 3。
r($tasks) && p('0:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。
r($tasks) && p('1:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('7,这个是测试用例7,3,other,no,blocked,user1,pass');       // 查看测试单 1 可关联的用例 7 的详细信息。
r($tasks) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('8,这个是测试用例8,4,feature,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 8 的详细信息。

$testtask->lang->navGroup->testtask = 'project';
$testtask->session->set('project', 2);
$tasks = $testtask->getLinkableCasesByTestTask(1);
r(count($tasks)) && p() && e(4); // 测试单 1 在项目 2 中可关联的用例数为 4。
r($tasks) && p('0:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。
r($tasks) && p('1:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('7,这个是测试用例7,3,other,no,blocked,user1,pass');       // 查看测试单 1 可关联的用例 7 的详细信息。
r($tasks) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('8,这个是测试用例8,4,feature,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 8 的详细信息。
