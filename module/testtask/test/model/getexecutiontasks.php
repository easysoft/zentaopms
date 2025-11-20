#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('testtask')->loadYaml('testtask')->gen(20);
zenData('build')->gen(1);
zenData('product')->gen(1);

/**

title=测试 testtaskModel->getExecutionTasks();
timeout=0
cid=19172

- 执行 0 的测试单数量为 0。 @0
- 执行 4 的测试单数量为 0。 @0
- 项目 1 的测试单数量为 12。 @12
- 项目 1 测试单 2 的详细信息。
 - 第2条的product属性 @1
 - 第2条的productName属性 @正常产品1
 - 第2条的project属性 @1
 - 第2条的execution属性 @2
 - 第2条的build属性 @1
 - 第2条的buildName属性 @项目11版本1
 - 第2条的name属性 @测试单2
 - 第2条的owner属性 @user2
 - 第2条的pri属性 @2
 - 第2条的status属性 @doing
- 项目 1 测试单 3 的详细信息。
 - 第3条的product属性 @1
 - 第3条的productName属性 @正常产品1
 - 第3条的project属性 @1
 - 第3条的execution属性 @2
 - 第3条的build属性 @1
 - 第3条的buildName属性 @项目11版本1
 - 第3条的name属性 @测试单3
 - 第3条的owner属性 @user3
 - 第3条的pri属性 @3
 - 第3条的status属性 @done
- 项目 1 测试单 4 的详细信息。
 - 第4条的product属性 @1
 - 第4条的productName属性 @正常产品1
 - 第4条的project属性 @1
 - 第4条的execution属性 @2
 - 第4条的build属性 @1
 - 第4条的buildName属性 @项目11版本1
 - 第4条的name属性 @测试单4
 - 第4条的owner属性 @user4
 - 第4条的pri属性 @4
 - 第4条的status属性 @blocked
- 执行 2 的测试单数量为 6。 @6
- 执行 2 测试单 2 的详细信息。
 - 第2条的product属性 @1
 - 第2条的productName属性 @正常产品1
 - 第2条的project属性 @1
 - 第2条的execution属性 @2
 - 第2条的build属性 @1
 - 第2条的buildName属性 @项目11版本1
 - 第2条的name属性 @测试单2
 - 第2条的owner属性 @user2
 - 第2条的pri属性 @2
 - 第2条的status属性 @doing
- 执行 2 测试单 3 的详细信息。
 - 第3条的product属性 @1
 - 第3条的productName属性 @正常产品1
 - 第3条的project属性 @1
 - 第3条的execution属性 @2
 - 第3条的build属性 @1
 - 第3条的buildName属性 @项目11版本1
 - 第3条的name属性 @测试单3
 - 第3条的owner属性 @user3
 - 第3条的pri属性 @3
 - 第3条的status属性 @done
- 执行 2 测试单 4 的详细信息。
 - 第4条的product属性 @1
 - 第4条的productName属性 @正常产品1
 - 第4条的project属性 @1
 - 第4条的execution属性 @2
 - 第4条的build属性 @1
 - 第4条的buildName属性 @项目11版本1
 - 第4条的name属性 @测试单4
 - 第4条的owner属性 @user4
 - 第4条的pri属性 @4
 - 第4条的status属性 @blocked
- 执行 3 的测试单数量为 6。 @6
- 执行 3 测试单 12 的详细信息。
 - 第12条的product属性 @1
 - 第12条的productName属性 @正常产品1
 - 第12条的project属性 @1
 - 第12条的execution属性 @3
 - 第12条的build属性 @1
 - 第12条的buildName属性 @项目11版本1
 - 第12条的name属性 @测试单12
 - 第12条的owner属性 @user3
 - 第12条的pri属性 @4
 - 第12条的status属性 @blocked
- 执行 3 测试单 13 的详细信息。
 - 第13条的product属性 @1
 - 第13条的productName属性 @正常产品1
 - 第13条的project属性 @1
 - 第13条的execution属性 @3
 - 第13条的build属性 @1
 - 第13条的buildName属性 @项目11版本1
 - 第13条的name属性 @测试单13
 - 第13条的owner属性 @user4
 - 第13条的pri属性 @1
 - 第13条的status属性 @wait
- 执行 3 测试单 14 的详细信息。
 - 第14条的product属性 @1
 - 第14条的productName属性 @正常产品1
 - 第14条的project属性 @1
 - 第14条的execution属性 @3
 - 第14条的build属性 @1
 - 第14条的buildName属性 @项目11版本1
 - 第14条的name属性 @测试单14
 - 第14条的owner属性 @user5
 - 第14条的pri属性 @2
 - 第14条的status属性 @doing
- 执行 3 的测试单数量为 6。 @6
- 执行 3 测试单 12 的详细信息。
 - 第12条的product属性 @1
 - 第12条的productName属性 @正常产品1
 - 第12条的project属性 @1
 - 第12条的execution属性 @3
 - 第12条的build属性 @1
 - 第12条的buildName属性 @项目11版本1
 - 第12条的name属性 @测试单12
 - 第12条的owner属性 @user3
 - 第12条的pri属性 @4
 - 第12条的status属性 @blocked
- 执行 3 测试单 13 的详细信息。
 - 第13条的product属性 @1
 - 第13条的productName属性 @正常产品1
 - 第13条的project属性 @1
 - 第13条的execution属性 @3
 - 第13条的build属性 @1
 - 第13条的buildName属性 @项目11版本1
 - 第13条的name属性 @测试单13
 - 第13条的owner属性 @user4
 - 第13条的pri属性 @1
 - 第13条的status属性 @wait
- 执行 3 测试单 14 的详细信息。
 - 第14条的product属性 @1
 - 第14条的productName属性 @正常产品1
 - 第14条的project属性 @1
 - 第14条的execution属性 @3
 - 第14条的build属性 @1
 - 第14条的buildName属性 @项目11版本1
 - 第14条的name属性 @测试单14
 - 第14条的owner属性 @user5
 - 第14条的pri属性 @2
 - 第14条的status属性 @doing
- 按 id 倒序排列，不分页，执行 3 的测试单数量为 6。 @6
- 按 id 倒序排列，不分页，执行 3 测试单第 1 条是  19。
 - 第0条的product属性 @1
 - 第0条的productName属性 @正常产品1
 - 第0条的project属性 @1
 - 第0条的execution属性 @3
 - 第0条的build属性 @1
 - 第0条的buildName属性 @项目11版本1
 - 第0条的name属性 @测试单19
 - 第0条的owner属性 @user1
 - 第0条的pri属性 @3
 - 第0条的status属性 @done
- 按 id 倒序排列，不分页，执行 3 测试单第 2 条是  18。
 - 第1条的product属性 @1
 - 第1条的productName属性 @正常产品1
 - 第1条的project属性 @1
 - 第1条的execution属性 @3
 - 第1条的build属性 @1
 - 第1条的buildName属性 @项目11版本1
 - 第1条的name属性 @测试单18
 - 第1条的owner属性 @user9
 - 第1条的pri属性 @2
 - 第1条的status属性 @doing
- 按 id 倒序排列，不分页，执行 3 测试单第 3 条是  17。
 - 第2条的product属性 @1
 - 第2条的productName属性 @正常产品1
 - 第2条的project属性 @1
 - 第2条的execution属性 @3
 - 第2条的build属性 @1
 - 第2条的buildName属性 @项目11版本1
 - 第2条的name属性 @测试单17
 - 第2条的owner属性 @user8
 - 第2条的pri属性 @1
 - 第2条的status属性 @wait
- 按 id 正序排列，不分页，执行 3 的测试单数量为 6。 @6
- 按 id 正序排列，不分页，执行 3 测试单第 1 条是  12。
 - 第0条的product属性 @1
 - 第0条的productName属性 @正常产品1
 - 第0条的project属性 @1
 - 第0条的execution属性 @3
 - 第0条的build属性 @1
 - 第0条的buildName属性 @项目11版本1
 - 第0条的name属性 @测试单12
 - 第0条的owner属性 @user3
 - 第0条的pri属性 @4
 - 第0条的status属性 @blocked
- 按 id 正序排列，不分页，执行 3 测试单第 2 条是  13。
 - 第1条的product属性 @1
 - 第1条的productName属性 @正常产品1
 - 第1条的project属性 @1
 - 第1条的execution属性 @3
 - 第1条的build属性 @1
 - 第1条的buildName属性 @项目11版本1
 - 第1条的name属性 @测试单13
 - 第1条的owner属性 @user4
 - 第1条的pri属性 @1
 - 第1条的status属性 @wait
- 按 id 正序排列，不分页，执行 3 测试单第 3 条是  14。
 - 第2条的product属性 @1
 - 第2条的productName属性 @正常产品1
 - 第2条的project属性 @1
 - 第2条的execution属性 @3
 - 第2条的build属性 @1
 - 第2条的buildName属性 @项目11版本1
 - 第2条的name属性 @测试单14
 - 第2条的owner属性 @user5
 - 第2条的pri属性 @2
 - 第2条的status属性 @doing
- 按 id 倒序排列，分页参数为 null，执行 3 的测试单数量为 6。 @6
- 按 id 倒序排列，分页参数为 null，执行 3 测试单第 1 条是  19。
 - 第0条的product属性 @1
 - 第0条的productName属性 @正常产品1
 - 第0条的project属性 @1
 - 第0条的execution属性 @3
 - 第0条的build属性 @1
 - 第0条的buildName属性 @项目11版本1
 - 第0条的name属性 @测试单19
 - 第0条的owner属性 @user1
 - 第0条的pri属性 @3
 - 第0条的status属性 @done
- 按 id 倒序排列，分页参数为 null，执行 3 测试单第 2 条是  18。
 - 第1条的product属性 @1
 - 第1条的productName属性 @正常产品1
 - 第1条的project属性 @1
 - 第1条的execution属性 @3
 - 第1条的build属性 @1
 - 第1条的buildName属性 @项目11版本1
 - 第1条的name属性 @测试单18
 - 第1条的owner属性 @user9
 - 第1条的pri属性 @2
 - 第1条的status属性 @doing
- 按 id 倒序排列，分页参数为 null，执行 3 测试单第 3 条是  17。
 - 第2条的product属性 @1
 - 第2条的productName属性 @正常产品1
 - 第2条的project属性 @1
 - 第2条的execution属性 @3
 - 第2条的build属性 @1
 - 第2条的buildName属性 @项目11版本1
 - 第2条的name属性 @测试单17
 - 第2条的owner属性 @user8
 - 第2条的pri属性 @1
 - 第2条的status属性 @wait
- 按 id 正序排列，分页参数为 null，执行 3 的测试单数量为 6。 @6
- 按 id 正序排列，分页参数为 null，执行 3 测试单第 1 条是  12。
 - 第0条的product属性 @1
 - 第0条的productName属性 @正常产品1
 - 第0条的project属性 @1
 - 第0条的execution属性 @3
 - 第0条的build属性 @1
 - 第0条的buildName属性 @项目11版本1
 - 第0条的name属性 @测试单12
 - 第0条的owner属性 @user3
 - 第0条的pri属性 @4
 - 第0条的status属性 @blocked
- 按 id 正序排列，分页参数为 null，执行 3 测试单第 2 条是  13。
 - 第1条的product属性 @1
 - 第1条的productName属性 @正常产品1
 - 第1条的project属性 @1
 - 第1条的execution属性 @3
 - 第1条的build属性 @1
 - 第1条的buildName属性 @项目11版本1
 - 第1条的name属性 @测试单13
 - 第1条的owner属性 @user4
 - 第1条的pri属性 @1
 - 第1条的status属性 @wait
- 按 id 正序排列，分页参数为 null，执行 3 测试单第 3 条是  14。
 - 第2条的product属性 @1
 - 第2条的productName属性 @正常产品1
 - 第2条的project属性 @1
 - 第2条的execution属性 @3
 - 第2条的build属性 @1
 - 第2条的buildName属性 @项目11版本1
 - 第2条的name属性 @测试单14
 - 第2条的owner属性 @user5
 - 第2条的pri属性 @2
 - 第2条的status属性 @doing
- 按 id 倒序排列，分页参数为每页 5 条，执行 3 的测试单数量为 5。 @5
- 按 id 倒序排列，分页参数为每页 5 条，执行 3 测试单第 1 条是  19。
 - 第0条的product属性 @1
 - 第0条的productName属性 @正常产品1
 - 第0条的project属性 @1
 - 第0条的execution属性 @3
 - 第0条的build属性 @1
 - 第0条的buildName属性 @项目11版本1
 - 第0条的name属性 @测试单19
 - 第0条的owner属性 @user1
 - 第0条的pri属性 @3
 - 第0条的status属性 @done
- 按 id 倒序排列，分页参数为每页 5 条，执行 3 测试单第 2 条是  18。
 - 第1条的product属性 @1
 - 第1条的productName属性 @正常产品1
 - 第1条的project属性 @1
 - 第1条的execution属性 @3
 - 第1条的build属性 @1
 - 第1条的buildName属性 @项目11版本1
 - 第1条的name属性 @测试单18
 - 第1条的owner属性 @user9
 - 第1条的pri属性 @2
 - 第1条的status属性 @doing
- 按 id 倒序排列，分页参数为每页 5 条，执行 3 测试单第 3 条是  17。
 - 第2条的product属性 @1
 - 第2条的productName属性 @正常产品1
 - 第2条的project属性 @1
 - 第2条的execution属性 @3
 - 第2条的build属性 @1
 - 第2条的buildName属性 @项目11版本1
 - 第2条的name属性 @测试单17
 - 第2条的owner属性 @user8
 - 第2条的pri属性 @1
 - 第2条的status属性 @wait
- 按 id 正序排列，分页参数为每页 5 条，执行 3 的测试单数量为 5。 @5
- 按 id 正序排列，分页参数为每页 5 条，执行 3 测试单第 1 条是 12。
 - 第0条的product属性 @1
 - 第0条的productName属性 @正常产品1
 - 第0条的project属性 @1
 - 第0条的execution属性 @3
 - 第0条的build属性 @1
 - 第0条的buildName属性 @项目11版本1
 - 第0条的name属性 @测试单12
 - 第0条的owner属性 @user3
 - 第0条的pri属性 @4
 - 第0条的status属性 @blocked
- 按 id 正序排列，分页参数为每页 5 条，执行 3 测试单第 2 条是 13。
 - 第1条的product属性 @1
 - 第1条的productName属性 @正常产品1
 - 第1条的project属性 @1
 - 第1条的execution属性 @3
 - 第1条的build属性 @1
 - 第1条的buildName属性 @项目11版本1
 - 第1条的name属性 @测试单13
 - 第1条的owner属性 @user4
 - 第1条的pri属性 @1
 - 第1条的status属性 @wait
- 按 id 正序排列，分页参数为每页 5 条，执行 3 测试单第 3 条是 14。
 - 第2条的product属性 @1
 - 第2条的productName属性 @正常产品1
 - 第2条的project属性 @1
 - 第2条的execution属性 @3
 - 第2条的build属性 @1
 - 第2条的buildName属性 @项目11版本1
 - 第2条的name属性 @测试单14
 - 第2条的owner属性 @user5
 - 第2条的pri属性 @2
 - 第2条的status属性 @doing

*/

global $tester, $app;

$app->rawModule = 'execution';
$app->rawMethod = 'testtask';
$app->loadClass('pager', true);
$pager = new pager(0, 5, 1);

$testtask = $tester->loadModel('testtask');

r($testtask->getExecutionTasks(0)) && p() && e(0); // 执行 0 的测试单数量为 0。
r($testtask->getExecutionTasks(4)) && p() && e(0); // 执行 4 的测试单数量为 0。

$tasks = $testtask->getExecutionTasks(1, 0, 'project');
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

$tasks = $testtask->getExecutionTasks(3, 0, 'execution');
r(count($tasks)) && p() && e(6); // 执行 3 的测试单数量为 6。
r($tasks) && p('12:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单12,user3,4,blocked'); // 执行 3 测试单 12 的详细信息。
r($tasks) && p('13:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单13,user4,1,wait');    // 执行 3 测试单 13 的详细信息。
r($tasks) && p('14:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单14,user5,2,doing');   // 执行 3 测试单 14 的详细信息。

$tasks = $testtask->getExecutionTasks(3, 0, 'execution', 'id_desc');
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(6); // 按 id 倒序排列，不分页，执行 3 的测试单数量为 6。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单19,user1,3,done');  // 按 id 倒序排列，不分页，执行 3 测试单第 1 条是  19。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单18,user9,2,doing'); // 按 id 倒序排列，不分页，执行 3 测试单第 2 条是  18。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单17,user8,1,wait');  // 按 id 倒序排列，不分页，执行 3 测试单第 3 条是  17。

$tasks = $testtask->getExecutionTasks(3, 0, 'execution', 'id_asc');
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(6); // 按 id 正序排列，不分页，执行 3 的测试单数量为 6。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单12,user3,4,blocked'); // 按 id 正序排列，不分页，执行 3 测试单第 1 条是  12。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单13,user4,1,wait');    // 按 id 正序排列，不分页，执行 3 测试单第 2 条是  13。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单14,user5,2,doing');   // 按 id 正序排列，不分页，执行 3 测试单第 3 条是  14。

$tasks = $testtask->getExecutionTasks(3, 0, 'execution', 'id_desc', null);
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(6); // 按 id 倒序排列，分页参数为 null，执行 3 的测试单数量为 6。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单19,user1,3,done');  // 按 id 倒序排列，分页参数为 null，执行 3 测试单第 1 条是  19。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单18,user9,2,doing'); // 按 id 倒序排列，分页参数为 null，执行 3 测试单第 2 条是  18。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单17,user8,1,wait');  // 按 id 倒序排列，分页参数为 null，执行 3 测试单第 3 条是  17。

$tasks = $testtask->getExecutionTasks(3, 0, 'execution', 'id_asc', null);
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(6); // 按 id 正序排列，分页参数为 null，执行 3 的测试单数量为 6。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单12,user3,4,blocked'); // 按 id 正序排列，分页参数为 null，执行 3 测试单第 1 条是  12。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单13,user4,1,wait');    // 按 id 正序排列，分页参数为 null，执行 3 测试单第 2 条是  13。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单14,user5,2,doing');   // 按 id 正序排列，分页参数为 null，执行 3 测试单第 3 条是  14。

$tasks = $testtask->getExecutionTasks(3, 0, 'execution', 'id_desc', $pager);
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(5); // 按 id 倒序排列，分页参数为每页 5 条，执行 3 的测试单数量为 5。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单19,user1,3,done');  // 按 id 倒序排列，分页参数为每页 5 条，执行 3 测试单第 1 条是  19。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单18,user9,2,doing'); // 按 id 倒序排列，分页参数为每页 5 条，执行 3 测试单第 2 条是  18。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单17,user8,1,wait');  // 按 id 倒序排列，分页参数为每页 5 条，执行 3 测试单第 3 条是  17。

$tasks = $testtask->getExecutionTasks(3, 0, 'execution', 'id_asc', $pager);
$tasks = array_values($tasks);
r(count($tasks)) && p() && e(5); // 按 id 正序排列，分页参数为每页 5 条，执行 3 的测试单数量为 5。
r($tasks) && p('0:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单12,user3,4,blocked'); // 按 id 正序排列，分页参数为每页 5 条，执行 3 测试单第 1 条是 12。
r($tasks) && p('1:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单13,user4,1,wait');    // 按 id 正序排列，分页参数为每页 5 条，执行 3 测试单第 2 条是 13。
r($tasks) && p('2:product,productName,project,execution,build,buildName,name,owner,pri,status') && e('1,正常产品1,1,3,1,项目11版本1,测试单14,user5,2,doing');   // 按 id 正序排列，分页参数为每页 5 条，执行 3 测试单第 3 条是 14。
