#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('testtask')->config('testtask')->gen(20);
zdTable('build')->gen(1);
zdTable('product')->gen(1);

/**

title=测试 testtaskModel->getExecutionTasks();
cid=1
pid=1

*/

global $tester, $app;

$app->setModuleName('execution');
$app->setMethodName('testtask');
$app->loadClass('pager', true);
$pager = new pager(0, 5, 1);

$testtask = $tester->loadModel('testtask');

r($testtask->getExecutionTasks(0)) && p() && e(0); // 执行 0 的测试单数量为 0。
r($testtask->getExecutionTasks(4)) && p() && e(0); // 执行 4 的测试单数量为 0。

$tasks = $testtask->getExecutionTasks(1, 'project');
r(count($tasks)) && p() && e(12); // 项目 1 的测试单数量为 12。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,2,1,项目11版本1,测试单2,user2,2,doing');   // 项目 1 测试单 2 的详细信息。
r($tasks) && p('3:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,2,1,项目11版本1,测试单3,user3,3,done');    // 项目 1 测试单 3 的详细信息。
r($tasks) && p('4:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,2,1,项目11版本1,测试单4,user4,4,blocked'); // 项目 1 测试单 4 的详细信息。

$tasks = $testtask->getExecutionTasks(2);
r(count($tasks)) && p() && e(6); // 执行 2 的测试单数量为 6。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,2,1,项目11版本1,测试单2,user2,2,doing');   // 执行 2 测试单 2 的详细信息。
r($tasks) && p('3:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,2,1,项目11版本1,测试单3,user3,3,done');    // 执行 2 测试单 3 的详细信息。
r($tasks) && p('4:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,2,1,项目11版本1,测试单4,user4,4,blocked'); // 执行 2 测试单 4 的详细信息。

$tasks = $testtask->getExecutionTasks(3);
r(count($tasks)) && p() && e(6); // 执行 3 的测试单数量为 6。
r($tasks) && p('12:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单12,user3,4,blocked'); // 执行 3 测试单 12 的详细信息。
r($tasks) && p('13:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单13,user4,1,wait');    // 执行 3 测试单 13 的详细信息。
r($tasks) && p('14:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单14,user5,2,doing');   // 执行 3 测试单 14 的详细信息。

$tasks = $testtask->getExecutionTasks(3, 'execution');
r(count($tasks)) && p() && e(6); // 执行 3 的测试单数量为 6。
r($tasks) && p('12:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单12,user3,4,blocked'); // 执行 3 测试单 12 的详细信息。
r($tasks) && p('13:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单13,user4,1,wait');    // 执行 3 测试单 13 的详细信息。
r($tasks) && p('14:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单14,user5,2,doing');   // 执行 3 测试单 14 的详细信息。

$tasks = $testtask->getExecutionTasks(3, 'execution', 'id_desc');
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(6); // 按 id 倒序排列，不分页，执行 3 的测试单数量为 6。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单19,user1,3,done');  // 按 id 倒序排列，不分页，执行 3 测试单第 1 条是  19。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单18,user9,2,doing'); // 按 id 倒序排列，不分页，执行 3 测试单第 2 条是  18。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单17,user8,1,wait');  // 按 id 倒序排列，不分页，执行 3 测试单第 3 条是  17。

$tasks = $testtask->getExecutionTasks(3, 'execution', 'id_asc');
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(6); // 按 id 正序排列，不分页，执行 3 的测试单数量为 6。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单12,user3,4,blocked'); // 按 id 正序排列，不分页，执行 3 测试单第 1 条是  12。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单13,user4,1,wait');    // 按 id 正序排列，不分页，执行 3 测试单第 2 条是  13。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单14,user5,2,doing');   // 按 id 正序排列，不分页，执行 3 测试单第 3 条是  14。

$tasks = $testtask->getExecutionTasks(3, 'execution', 'id_desc', null);
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(6); // 按 id 倒序排列，分页参数为 null，执行 3 的测试单数量为 6。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单19,user1,3,done');  // 按 id 倒序排列，分页参数为 null，执行 3 测试单第 1 条是  19。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单18,user9,2,doing'); // 按 id 倒序排列，分页参数为 null，执行 3 测试单第 2 条是  18。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单17,user8,1,wait');  // 按 id 倒序排列，分页参数为 null，执行 3 测试单第 3 条是  17。

$tasks = $testtask->getExecutionTasks(3, 'execution', 'id_asc', null);
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(6); // 按 id 正序排列，分页参数为 null，执行 3 的测试单数量为 6。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单12,user3,4,blocked'); // 按 id 正序排列，分页参数为 null，执行 3 测试单第 1 条是  12。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单13,user4,1,wait');    // 按 id 正序排列，分页参数为 null，执行 3 测试单第 2 条是  13。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单14,user5,2,doing');   // 按 id 正序排列，分页参数为 null，执行 3 测试单第 3 条是  14。

$tasks = $testtask->getExecutionTasks(3, 'execution', 'id_desc', $pager);
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(5); // 按 id 倒序排列，分页参数为每页 5 条，执行 3 的测试单数量为 5。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单19,user1,3,done');  // 按 id 倒序排列，分页参数为每页 5 条，执行 3 测试单第 1 条是  19。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单18,user9,2,doing'); // 按 id 倒序排列，分页参数为每页 5 条，执行 3 测试单第 2 条是  18。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单17,user8,1,wait');  // 按 id 倒序排列，分页参数为每页 5 条，执行 3 测试单第 3 条是  17。

$tasks = $testtask->getExecutionTasks(3, 'execution', 'id_asc', $pager);
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(5); // 按 id 正序排列，分页参数为每页 5 条，执行 3 的测试单数量为 5。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单12,user3,4,blocked'); // 按 id 正序排列，分页参数为每页 5 条，执行 3 测试单第 1 条是 12。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单13,user4,1,wait');    // 按 id 正序排列，分页参数为每页 5 条，执行 3 测试单第 2 条是 13。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单14,user5,2,doing');   // 按 id 正序排列，分页参数为每页 5 条，执行 3 测试单第 3 条是 14。
