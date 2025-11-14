#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('case')->loadYaml('case')->gen(10);
zenData('testrun')->loadYaml('testrun')->gen(10);

/**

title=测试 testtaskModel->getLinkableCasesByTestTask();
timeout=0
cid=19180

- 测试单 0 可关联的用例数为 0。 @0
- 测试单 2 可关联的用例数为 0。 @0
- 测试单 1 可关联的用例数为 7。 @7
- 查看测试单 1 可关联的用例 2 的详细信息。
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的pri属性 @2
 - 第2条的type属性 @performance
 - 第2条的auto属性 @no
 - 第2条的status属性 @normal
 - 第2条的lastRunner属性 @user1
 - 第2条的lastRunResult属性 @fail
- 查看测试单 1 可关联的用例 3 的详细信息。
 - 第3条的id属性 @3
 - 第3条的title属性 @这个是测试用例3
 - 第3条的pri属性 @3
 - 第3条的type属性 @config
 - 第3条的auto属性 @no
 - 第3条的status属性 @blocked
 - 第3条的lastRunner属性 @user1
 - 第3条的lastRunResult属性 @pass
- 查看测试单 1 可关联的用例 4 的详细信息。
 - 第4条的id属性 @4
 - 第4条的title属性 @这个是测试用例4
 - 第4条的pri属性 @4
 - 第4条的type属性 @install
 - 第4条的auto属性 @no
 - 第4条的status属性 @investigate
 - 第4条的lastRunner属性 @user1
 - 第4条的lastRunResult属性 @fail
- 测试单 1 查询 id > 10 的用例后可关联的用例数为 0。 @0
- 测试单 1 查询 id < 10 的用例后可关联的用例数为 6。 @6
- 查看测试单 1 可关联的用例 2 的详细信息。
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的pri属性 @2
 - 第2条的type属性 @performance
 - 第2条的auto属性 @no
 - 第2条的status属性 @normal
 - 第2条的lastRunner属性 @user1
 - 第2条的lastRunResult属性 @fail
- 查看测试单 1 可关联的用例 3 的详细信息。
 - 第3条的id属性 @3
 - 第3条的title属性 @这个是测试用例3
 - 第3条的pri属性 @3
 - 第3条的type属性 @config
 - 第3条的auto属性 @no
 - 第3条的status属性 @blocked
 - 第3条的lastRunner属性 @user1
 - 第3条的lastRunResult属性 @pass
- 查看测试单 1 可关联的用例 4 的详细信息。
 - 第4条的id属性 @4
 - 第4条的title属性 @这个是测试用例4
 - 第4条的pri属性 @4
 - 第4条的type属性 @install
 - 第4条的auto属性 @no
 - 第4条的status属性 @investigate
 - 第4条的lastRunner属性 @user1
 - 第4条的lastRunResult属性 @fail
- 测试单 1 查询 id < 10 的用例并排除用例 2 后可关联的用例数为 5。 @5
- 查看测试单 1 可关联的用例 3 的详细信息。
 - 第3条的id属性 @3
 - 第3条的title属性 @这个是测试用例3
 - 第3条的pri属性 @3
 - 第3条的type属性 @config
 - 第3条的auto属性 @no
 - 第3条的status属性 @blocked
 - 第3条的lastRunner属性 @user1
 - 第3条的lastRunResult属性 @pass
- 查看测试单 1 可关联的用例 4 的详细信息。
 - 第4条的id属性 @4
 - 第4条的title属性 @这个是测试用例4
 - 第4条的pri属性 @4
 - 第4条的type属性 @install
 - 第4条的auto属性 @no
 - 第4条的status属性 @investigate
 - 第4条的lastRunner属性 @user1
 - 第4条的lastRunResult属性 @fail
- 查看测试单 1 可关联的用例 6 的详细信息。
 - 第6条的id属性 @6
 - 第6条的title属性 @这个是测试用例6
 - 第6条的pri属性 @2
 - 第6条的type属性 @interface
 - 第6条的auto属性 @no
 - 第6条的status属性 @normal
 - 第6条的lastRunner属性 @user1
 - 第6条的lastRunResult属性 @fail
- 测试单 1 查询 id < 10 并限制每页查询 5 条的用例后可关联的用例数为 5。 @5
- 查看测试单 1 可关联的用例 2 的详细信息。
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的pri属性 @2
 - 第2条的type属性 @performance
 - 第2条的auto属性 @no
 - 第2条的status属性 @normal
 - 第2条的lastRunner属性 @user1
 - 第2条的lastRunResult属性 @fail
- 查看测试单 1 可关联的用例 3 的详细信息。
 - 第3条的id属性 @3
 - 第3条的title属性 @这个是测试用例3
 - 第3条的pri属性 @3
 - 第3条的type属性 @config
 - 第3条的auto属性 @no
 - 第3条的status属性 @blocked
 - 第3条的lastRunner属性 @user1
 - 第3条的lastRunResult属性 @pass
- 查看测试单 1 可关联的用例 4 的详细信息。
 - 第4条的id属性 @4
 - 第4条的title属性 @这个是测试用例4
 - 第4条的pri属性 @4
 - 第4条的type属性 @install
 - 第4条的auto属性 @no
 - 第4条的status属性 @investigate
 - 第4条的lastRunner属性 @user1
 - 第4条的lastRunResult属性 @fail
- 测试单 1 查询 id < 10 的用例、排除用例 2 并限制每页查询 5 条后可关联的用例数为 5。 @5
- 查看测试单 1 可关联的用例 3 的详细信息。
 - 第3条的id属性 @3
 - 第3条的title属性 @这个是测试用例3
 - 第3条的pri属性 @3
 - 第3条的type属性 @config
 - 第3条的auto属性 @no
 - 第3条的status属性 @blocked
 - 第3条的lastRunner属性 @user1
 - 第3条的lastRunResult属性 @pass
- 查看测试单 1 可关联的用例 4 的详细信息。
 - 第4条的id属性 @4
 - 第4条的title属性 @这个是测试用例4
 - 第4条的pri属性 @4
 - 第4条的type属性 @install
 - 第4条的auto属性 @no
 - 第4条的status属性 @investigate
 - 第4条的lastRunner属性 @user1
 - 第4条的lastRunResult属性 @fail
- 查看测试单 1 可关联的用例 6 的详细信息。
 - 第6条的id属性 @6
 - 第6条的title属性 @这个是测试用例6
 - 第6条的pri属性 @2
 - 第6条的type属性 @interface
 - 第6条的auto属性 @no
 - 第6条的status属性 @normal
 - 第6条的lastRunner属性 @user1
 - 第6条的lastRunResult属性 @fail
- 测试单 1 查询 id < 10 的用例、排除用例 2-4 并限制每页查询 5 条后可关联的用例数为 3。 @3
- 查看测试单 1 可关联的用例 6 的详细信息。
 - 第6条的id属性 @6
 - 第6条的title属性 @这个是测试用例6
 - 第6条的pri属性 @2
 - 第6条的type属性 @interface
 - 第6条的auto属性 @no
 - 第6条的status属性 @normal
 - 第6条的lastRunner属性 @user1
 - 第6条的lastRunResult属性 @fail
- 查看测试单 1 可关联的用例 7 的详细信息。
 - 第7条的id属性 @7
 - 第7条的title属性 @这个是测试用例7
 - 第7条的pri属性 @3
 - 第7条的type属性 @other
 - 第7条的auto属性 @no
 - 第7条的status属性 @blocked
 - 第7条的lastRunner属性 @user1
 - 第7条的lastRunResult属性 @pass
- 查看测试单 1 可关联的用例 8 的详细信息。
 - 第8条的id属性 @8
 - 第8条的title属性 @这个是测试用例8
 - 第8条的pri属性 @4
 - 第8条的type属性 @feature
 - 第8条的auto属性 @no
 - 第8条的status属性 @investigate
 - 第8条的lastRunner属性 @user1
 - 第8条的lastRunResult属性 @fail
- 测试单 1 排除用例 1-10 后可关联的用例数为 0。 @0
- 测试单 1 排除用例 2 后可关联的用例数为 6。 @6
- 查看测试单 1 可关联的用例 3 的详细信息。
 - 第3条的id属性 @3
 - 第3条的title属性 @这个是测试用例3
 - 第3条的pri属性 @3
 - 第3条的type属性 @config
 - 第3条的auto属性 @no
 - 第3条的status属性 @blocked
 - 第3条的lastRunner属性 @user1
 - 第3条的lastRunResult属性 @pass
- 查看测试单 1 可关联的用例 4 的详细信息。
 - 第4条的id属性 @4
 - 第4条的title属性 @这个是测试用例4
 - 第4条的pri属性 @4
 - 第4条的type属性 @install
 - 第4条的auto属性 @no
 - 第4条的status属性 @investigate
 - 第4条的lastRunner属性 @user1
 - 第4条的lastRunResult属性 @fail
- 查看测试单 1 可关联的用例 6 的详细信息。
 - 第6条的id属性 @6
 - 第6条的title属性 @这个是测试用例6
 - 第6条的pri属性 @2
 - 第6条的type属性 @interface
 - 第6条的auto属性 @no
 - 第6条的status属性 @normal
 - 第6条的lastRunner属性 @user1
 - 第6条的lastRunResult属性 @fail
- 测试单 1 排除用例 2 并限制每页查询 5 条后可关联的用例数为 5。 @5
- 查看测试单 1 可关联的用例 3 的详细信息。
 - 第3条的id属性 @3
 - 第3条的title属性 @这个是测试用例3
 - 第3条的pri属性 @3
 - 第3条的type属性 @config
 - 第3条的auto属性 @no
 - 第3条的status属性 @blocked
 - 第3条的lastRunner属性 @user1
 - 第3条的lastRunResult属性 @pass
- 查看测试单 1 可关联的用例 4 的详细信息。
 - 第4条的id属性 @4
 - 第4条的title属性 @这个是测试用例4
 - 第4条的pri属性 @4
 - 第4条的type属性 @install
 - 第4条的auto属性 @no
 - 第4条的status属性 @investigate
 - 第4条的lastRunner属性 @user1
 - 第4条的lastRunResult属性 @fail
- 查看测试单 1 可关联的用例 6 的详细信息。
 - 第6条的id属性 @6
 - 第6条的title属性 @这个是测试用例6
 - 第6条的pri属性 @2
 - 第6条的type属性 @interface
 - 第6条的auto属性 @no
 - 第6条的status属性 @normal
 - 第6条的lastRunner属性 @user1
 - 第6条的lastRunResult属性 @fail
- 测试单 1 限制每页查询 5 条后可关联的用例数为 5。 @5
- 查看测试单 1 可关联的用例 2 的详细信息。
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的pri属性 @2
 - 第2条的type属性 @performance
 - 第2条的auto属性 @no
 - 第2条的status属性 @normal
 - 第2条的lastRunner属性 @user1
 - 第2条的lastRunResult属性 @fail
- 查看测试单 1 可关联的用例 3 的详细信息。
 - 第3条的id属性 @3
 - 第3条的title属性 @这个是测试用例3
 - 第3条的pri属性 @3
 - 第3条的type属性 @config
 - 第3条的auto属性 @no
 - 第3条的status属性 @blocked
 - 第3条的lastRunner属性 @user1
 - 第3条的lastRunResult属性 @pass
- 查看测试单 1 可关联的用例 4 的详细信息。
 - 第4条的id属性 @4
 - 第4条的title属性 @这个是测试用例4
 - 第4条的pri属性 @4
 - 第4条的type属性 @install
 - 第4条的auto属性 @no
 - 第4条的status属性 @investigate
 - 第4条的lastRunner属性 @user1
 - 第4条的lastRunResult属性 @fail
- 测试单 1 在项目 2 中可关联的用例数为 4。 @4
- 查看测试单 1 可关联的用例 6 的详细信息。
 - 第6条的id属性 @6
 - 第6条的title属性 @这个是测试用例6
 - 第6条的pri属性 @2
 - 第6条的type属性 @interface
 - 第6条的auto属性 @no
 - 第6条的status属性 @normal
 - 第6条的lastRunner属性 @user1
 - 第6条的lastRunResult属性 @fail
- 查看测试单 1 可关联的用例 7 的详细信息。
 - 第7条的id属性 @7
 - 第7条的title属性 @这个是测试用例7
 - 第7条的pri属性 @3
 - 第7条的type属性 @other
 - 第7条的auto属性 @no
 - 第7条的status属性 @blocked
 - 第7条的lastRunner属性 @user1
 - 第7条的lastRunResult属性 @pass
- 查看测试单 1 可关联的用例 8 的详细信息。
 - 第8条的id属性 @8
 - 第8条的title属性 @这个是测试用例8
 - 第8条的pri属性 @4
 - 第8条的type属性 @feature
 - 第8条的auto属性 @no
 - 第8条的status属性 @investigate
 - 第8条的lastRunner属性 @user1
 - 第8条的lastRunResult属性 @fail

*/

global $tester, $app;

$app->rawModule = 'testtask';
$app->rawMethod = 'linCase';
$app->loadClass('pager', true);
$pager = new pager(0, 5, 1);

$testtask = $tester->loadModel('testtask');

r($testtask->getLinkableCasesByTestTask(0)) && p() && e(0); // 测试单 0 可关联的用例数为 0。
r($testtask->getLinkableCasesByTestTask(2)) && p() && e(0); // 测试单 2 可关联的用例数为 0。

$cases = $testtask->getLinkableCasesByTestTask(1);
r(count($cases)) && p() && e(7); // 测试单 1 可关联的用例数为 7。
r($cases) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('2,这个是测试用例2,2,performance,no,normal,user1,fail');  // 查看测试单 1 可关联的用例 2 的详细信息。
r($cases) && p('3:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($cases) && p('4:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。

$cases = $testtask->getLinkableCasesByTestTask(1, 't1.id > 10');
r(count($cases)) && p() && e(0); // 测试单 1 查询 id > 10 的用例后可关联的用例数为 0。

$cases = $testtask->getLinkableCasesByTestTask(1, 't1.id < 10');
r(count($cases)) && p() && e(6); // 测试单 1 查询 id < 10 的用例后可关联的用例数为 6。
r($cases) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('2,这个是测试用例2,2,performance,no,normal,user1,fail');  // 查看测试单 1 可关联的用例 2 的详细信息。
r($cases) && p('3:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($cases) && p('4:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。

$cases = $testtask->getLinkableCasesByTestTask(1, 't1.id < 10', array(2));
r(count($cases)) && p() && e(5); // 测试单 1 查询 id < 10 的用例并排除用例 2 后可关联的用例数为 5。
r($cases) && p('3:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($cases) && p('4:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。
r($cases) && p('6:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。

$cases = $testtask->getLinkableCasesByTestTask(1, 't1.id < 10', array(), $pager);
r(count($cases)) && p() && e(5); // 测试单 1 查询 id < 10 并限制每页查询 5 条的用例后可关联的用例数为 5。
r($cases) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('2,这个是测试用例2,2,performance,no,normal,user1,fail');  // 查看测试单 1 可关联的用例 2 的详细信息。
r($cases) && p('3:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($cases) && p('4:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。

$cases = $testtask->getLinkableCasesByTestTask(1, 't1.id < 10', array(2), $pager);
r(count($cases)) && p() && e(5); // 测试单 1 查询 id < 10 的用例、排除用例 2 并限制每页查询 5 条后可关联的用例数为 5。
r($cases) && p('3:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($cases) && p('4:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。
r($cases) && p('6:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。

$cases = $testtask->getLinkableCasesByTestTask(1, 't1.id < 10', array(2,3,4), $pager);
r(count($cases)) && p() && e(3); // 测试单 1 查询 id < 10 的用例、排除用例 2-4 并限制每页查询 5 条后可关联的用例数为 3。
r($cases) && p('6:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。
r($cases) && p('7:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('7,这个是测试用例7,3,other,no,blocked,user1,pass');       // 查看测试单 1 可关联的用例 7 的详细信息。
r($cases) && p('8:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('8,这个是测试用例8,4,feature,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 8 的详细信息。

$cases = $testtask->getLinkableCasesByTestTask(1, '', array(1,2,3,4,5,6,7,8,9,10));
r(count($cases)) && p() && e(0); // 测试单 1 排除用例 1-10 后可关联的用例数为 0。

$cases = $testtask->getLinkableCasesByTestTask(1, '', array(2));
r(count($cases)) && p() && e(6); // 测试单 1 排除用例 2 后可关联的用例数为 6。
r($cases) && p('3:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($cases) && p('4:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。
r($cases) && p('6:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。

$cases = $testtask->getLinkableCasesByTestTask(1, '', array(2), $pager);
r(count($cases)) && p() && e(5); // 测试单 1 排除用例 2 并限制每页查询 5 条后可关联的用例数为 5。
r($cases) && p('3:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($cases) && p('4:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。
r($cases) && p('6:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。

$cases = $testtask->getLinkableCasesByTestTask(1, '', array(), $pager);
r(count($cases)) && p() && e(5); // 测试单 1 限制每页查询 5 条后可关联的用例数为 5。
r($cases) && p('2:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('2,这个是测试用例2,2,performance,no,normal,user1,fail');  // 查看测试单 1 可关联的用例 2 的详细信息。
r($cases) && p('3:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('3,这个是测试用例3,3,config,no,blocked,user1,pass');      // 查看测试单 1 可关联的用例 3 的详细信息。
r($cases) && p('4:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('4,这个是测试用例4,4,install,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 4 的详细信息。

$testtask->lang->navGroup->testtask = 'project';
$testtask->session->set('project', 2);
$cases = $testtask->getLinkableCasesByTestTask(1);
r(count($cases)) && p() && e(4); // 测试单 1 在项目 2 中可关联的用例数为 4。
r($cases) && p('6:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('6,这个是测试用例6,2,interface,no,normal,user1,fail');    // 查看测试单 1 可关联的用例 6 的详细信息。
r($cases) && p('7:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('7,这个是测试用例7,3,other,no,blocked,user1,pass');       // 查看测试单 1 可关联的用例 7 的详细信息。
r($cases) && p('8:id,title,pri,type,auto,status,lastRunner,lastRunResult') && e('8,这个是测试用例8,4,feature,no,investigate,user1,fail'); // 查看测试单 1 可关联的用例 8 的详细信息。
