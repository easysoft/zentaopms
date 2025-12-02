#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('case')->loadYaml('case')->gen(20);
zenData('build')->loadYaml('build')->gen(3);
zenData('story')->gen(2);

/**

title=测试 testtaskModel->getLinkableCasesByStory();
timeout=0
cid=19178

- 产品 0 测试单 4 可关联的用例数为 0。 @0
- 产品 2 测试单 4 可关联的用例数为 0。 @0
- 产品 1 测试单 1 可关联的用例数为 0。 @0
- 产品 1 测试单 2 可关联的用例数为 0。 @0
- 产品 1 测试单 3 可关联的用例数为 0。 @0
- 产品 1 测试单 4 可关联的用例数为 6。 @6
- 查看可关联的用例 8 的详细信息。
 - 第8条的id属性 @8
 - 第8条的title属性 @这个是测试用例8
 - 第8条的pri属性 @4
 - 第8条的type属性 @feature
 - 第8条的auto属性 @no
 - 第8条的status属性 @investigate
 - 第8条的storyTitle属性 @软件需求2
- 查看可关联的用例 7 的详细信息。
 - 第7条的id属性 @7
 - 第7条的title属性 @这个是测试用例7
 - 第7条的pri属性 @3
 - 第7条的type属性 @other
 - 第7条的auto属性 @no
 - 第7条的status属性 @blocked
 - 第7条的storyTitle属性 @用户需求1
- 查看可关联的用例 6 的详细信息。
 - 第6条的id属性 @6
 - 第6条的title属性 @这个是测试用例6
 - 第6条的pri属性 @2
 - 第6条的type属性 @interface
 - 第6条的auto属性 @no
 - 第6条的status属性 @normal
 - 第6条的storyTitle属性 @软件需求2
- 产品 1 测试单 5 可关联的用例数为 12。 @12
- 查看可关联的用例 19 的详细信息。
 - 第19条的id属性 @19
 - 第19条的title属性 @这个是测试用例19
 - 第19条的pri属性 @3
 - 第19条的type属性 @security
 - 第19条的auto属性 @no
 - 第19条的status属性 @blocked
 - 第19条的storyTitle属性 @用户需求1
- 查看可关联的用例 18 的详细信息。
 - 第18条的id属性 @18
 - 第18条的title属性 @这个是测试用例18
 - 第18条的pri属性 @2
 - 第18条的type属性 @install
 - 第18条的auto属性 @no
 - 第18条的status属性 @normal
 - 第18条的storyTitle属性 @软件需求2
- 查看可关联的用例 16 的详细信息。
 - 第16条的id属性 @16
 - 第16条的title属性 @这个是测试用例16
 - 第16条的pri属性 @4
 - 第16条的type属性 @performance
 - 第16条的auto属性 @no
 - 第16条的status属性 @investigate
 - 第16条的storyTitle属性 @软件需求2
- 产品 1 测试单 5 查询 id > 19 后可关联的用例数为 0。 @0
- 产品 1 测试单 5 查询 id < 19 后可关联的用例数为 11。 @11
- 查看可关联的用例 18 的详细信息。
 - 第18条的id属性 @18
 - 第18条的title属性 @这个是测试用例18
 - 第18条的pri属性 @2
 - 第18条的type属性 @install
 - 第18条的auto属性 @no
 - 第18条的status属性 @normal
 - 第18条的storyTitle属性 @软件需求2
- 查看可关联的用例 16 的详细信息。
 - 第16条的id属性 @16
 - 第16条的title属性 @这个是测试用例16
 - 第16条的pri属性 @4
 - 第16条的type属性 @performance
 - 第16条的auto属性 @no
 - 第16条的status属性 @investigate
 - 第16条的storyTitle属性 @软件需求2
- 查看可关联的用例 14 的详细信息。
 - 第14条的id属性 @14
 - 第14条的title属性 @这个是测试用例14
 - 第14条的pri属性 @2
 - 第14条的type属性 @other
 - 第14条的auto属性 @no
 - 第14条的status属性 @normal
 - 第14条的storyTitle属性 @软件需求2
- 产品 1 测试单 5 查询 id < 19 并排除用例 2 后可关联的用例数为 10。 @10
- 查看可关联的用例 18 的详细信息。
 - 第18条的id属性 @18
 - 第18条的title属性 @这个是测试用例18
 - 第18条的pri属性 @2
 - 第18条的type属性 @install
 - 第18条的auto属性 @no
 - 第18条的status属性 @normal
 - 第18条的storyTitle属性 @软件需求2
- 查看可关联的用例 16 的详细信息。
 - 第16条的id属性 @16
 - 第16条的title属性 @这个是测试用例16
 - 第16条的pri属性 @4
 - 第16条的type属性 @performance
 - 第16条的auto属性 @no
 - 第16条的status属性 @investigate
 - 第16条的storyTitle属性 @软件需求2
- 查看可关联的用例 14 的详细信息。
 - 第14条的id属性 @14
 - 第14条的title属性 @这个是测试用例14
 - 第14条的pri属性 @2
 - 第14条的type属性 @other
 - 第14条的auto属性 @no
 - 第14条的status属性 @normal
 - 第14条的storyTitle属性 @软件需求2
- 产品 1 测试单 5 查询 id < 19 并限制每页查询 5 条后可关联的用例数为 5。 @5
- 查看可关联的用例 18 的详细信息。
 - 第18条的id属性 @18
 - 第18条的title属性 @这个是测试用例18
 - 第18条的pri属性 @2
 - 第18条的type属性 @install
 - 第18条的auto属性 @no
 - 第18条的status属性 @normal
 - 第18条的storyTitle属性 @软件需求2
- 查看可关联的用例 16 的详细信息。
 - 第16条的id属性 @16
 - 第16条的title属性 @这个是测试用例16
 - 第16条的pri属性 @4
 - 第16条的type属性 @performance
 - 第16条的auto属性 @no
 - 第16条的status属性 @investigate
 - 第16条的storyTitle属性 @软件需求2
- 查看可关联的用例 14 的详细信息。
 - 第14条的id属性 @14
 - 第14条的title属性 @这个是测试用例14
 - 第14条的pri属性 @2
 - 第14条的type属性 @other
 - 第14条的auto属性 @no
 - 第14条的status属性 @normal
 - 第14条的storyTitle属性 @软件需求2
- 产品 1 测试单 5 查询 id < 19、排除用例 2 并限制每页查询 5 条后可关联的用例数为 5。 @5
- 查看可关联的用例 18 的详细信息。
 - 第18条的id属性 @18
 - 第18条的title属性 @这个是测试用例18
 - 第18条的pri属性 @2
 - 第18条的type属性 @install
 - 第18条的auto属性 @no
 - 第18条的status属性 @normal
 - 第18条的storyTitle属性 @软件需求2
- 查看可关联的用例 16 的详细信息。
 - 第16条的id属性 @16
 - 第16条的title属性 @这个是测试用例16
 - 第16条的pri属性 @4
 - 第16条的type属性 @performance
 - 第16条的auto属性 @no
 - 第16条的status属性 @investigate
 - 第16条的storyTitle属性 @软件需求2
- 查看可关联的用例 14 的详细信息。
 - 第14条的id属性 @14
 - 第14条的title属性 @这个是测试用例14
 - 第14条的pri属性 @2
 - 第14条的type属性 @other
 - 第14条的auto属性 @no
 - 第14条的status属性 @normal
 - 第14条的storyTitle属性 @软件需求2
- 产品 1 测试单 5 查询 id < 19、排除用例 2-12 并限制每页查询 5 条后可关联的用例数为 3。 @3
- 查看可关联的用例 18 的详细信息。
 - 第18条的id属性 @18
 - 第18条的title属性 @这个是测试用例18
 - 第18条的pri属性 @2
 - 第18条的type属性 @install
 - 第18条的auto属性 @no
 - 第18条的status属性 @normal
 - 第18条的storyTitle属性 @软件需求2
- 查看可关联的用例 16 的详细信息。
 - 第16条的id属性 @16
 - 第16条的title属性 @这个是测试用例16
 - 第16条的pri属性 @4
 - 第16条的type属性 @performance
 - 第16条的auto属性 @no
 - 第16条的status属性 @investigate
 - 第16条的storyTitle属性 @软件需求2
- 查看可关联的用例 14 的详细信息。
 - 第14条的id属性 @14
 - 第14条的title属性 @这个是测试用例14
 - 第14条的pri属性 @2
 - 第14条的type属性 @other
 - 第14条的auto属性 @no
 - 第14条的status属性 @normal
 - 第14条的storyTitle属性 @软件需求2
- 产品 1 测试单 5 在项目 2 中可关联的用例数为 6。 @6
- 查看可关联的用例 19 的详细信息。
 - 第19条的id属性 @19
 - 第19条的title属性 @这个是测试用例19
 - 第19条的pri属性 @3
 - 第19条的type属性 @security
 - 第19条的auto属性 @no
 - 第19条的status属性 @blocked
 - 第19条的storyTitle属性 @用户需求1
- 查看可关联的用例 18 的详细信息。
 - 第18条的id属性 @18
 - 第18条的title属性 @这个是测试用例18
 - 第18条的pri属性 @2
 - 第18条的type属性 @install
 - 第18条的auto属性 @no
 - 第18条的status属性 @normal
 - 第18条的storyTitle属性 @软件需求2
- 查看可关联的用例 16 的详细信息。
 - 第16条的id属性 @16
 - 第16条的title属性 @这个是测试用例16
 - 第16条的pri属性 @4
 - 第16条的type属性 @performance
 - 第16条的auto属性 @no
 - 第16条的status属性 @investigate
 - 第16条的storyTitle属性 @软件需求2

*/

global $tester, $app;

$app->rawModule = 'testtask';
$app->rawMethod = 'linkCase';
$app->loadClass('pager', true);
$pager = new pager(0, 5, 1);

$testtask = $tester->loadModel('testtask');

$task1 = (object)array('build' => 0, 'branch' => 0); // 测试单 1 版本 0 分支 0 没有用例。
$task2 = (object)array('build' => 1, 'branch' => 0); // 测试单 2 版本 1 分支 0 没有用例。
$task3 = (object)array('build' => 3, 'branch' => 0); // 测试单 3 版本 3 分支 0 没有用例。
$task4 = (object)array('build' => 2, 'branch' => 0); // 测试单 4 版本 2 分支 0 有用例。
$task5 = (object)array('build' => 2, 'branch' => 1); // 测试单 5 版本 2 分支 1 有用例。

r($testtask->getLinkableCasesByStory(0, $task4)) && p() && e(0); // 产品 0 测试单 4 可关联的用例数为 0。
r($testtask->getLinkableCasesByStory(2, $task4)) && p() && e(0); // 产品 2 测试单 4 可关联的用例数为 0。
r($testtask->getLinkableCasesByStory(1, $task1)) && p() && e(0); // 产品 1 测试单 1 可关联的用例数为 0。
r($testtask->getLinkableCasesByStory(1, $task2)) && p() && e(0); // 产品 1 测试单 2 可关联的用例数为 0。
r($testtask->getLinkableCasesByStory(1, $task3)) && p() && e(0); // 产品 1 测试单 3 可关联的用例数为 0。

$cases = $testtask->getLinkableCasesByStory(1, $task4);
r(count($cases)) && p() && e(6); // 产品 1 测试单 4 可关联的用例数为 6。
r($cases) && p('8:id,title,pri,type,auto,status,storyTitle') && e('8,这个是测试用例8,4,feature,no,investigate,软件需求2'); // 查看可关联的用例 8 的详细信息。
r($cases) && p('7:id,title,pri,type,auto,status,storyTitle') && e('7,这个是测试用例7,3,other,no,blocked,用户需求1');       // 查看可关联的用例 7 的详细信息。
r($cases) && p('6:id,title,pri,type,auto,status,storyTitle') && e('6,这个是测试用例6,2,interface,no,normal,软件需求2');    // 查看可关联的用例 6 的详细信息。

$cases = $testtask->getLinkableCasesByStory(1, $task5);
r(count($cases)) && p() && e(12); // 产品 1 测试单 5 可关联的用例数为 12。
r($cases) && p('19:id,title,pri,type,auto,status,storyTitle') && e('19,这个是测试用例19,3,security,no,blocked,用户需求1');        // 查看可关联的用例 19 的详细信息。
r($cases) && p('18:id,title,pri,type,auto,status,storyTitle') && e('18,这个是测试用例18,2,install,no,normal,软件需求2');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('16:id,title,pri,type,auto,status,storyTitle') && e('16,这个是测试用例16,4,performance,no,investigate,软件需求2'); // 查看可关联的用例 16 的详细信息。

$cases = $testtask->getLinkableCasesByStory(1, $task5, 't1.id > 19');
r(count($cases)) && p() && e(0); // 产品 1 测试单 5 查询 id > 19 后可关联的用例数为 0。

$cases = $testtask->getLinkableCasesByStory(1, $task5, 't1.id < 19');
r(count($cases)) && p() && e(11); // 产品 1 测试单 5 查询 id < 19 后可关联的用例数为 11。
r($cases) && p('18:id,title,pri,type,auto,status,storyTitle') && e('18,这个是测试用例18,2,install,no,normal,软件需求2');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('16:id,title,pri,type,auto,status,storyTitle') && e('16,这个是测试用例16,4,performance,no,investigate,软件需求2'); // 查看可关联的用例 16 的详细信息。
r($cases) && p('14:id,title,pri,type,auto,status,storyTitle') && e('14,这个是测试用例14,2,other,no,normal,软件需求2');            // 查看可关联的用例 14 的详细信息。

$cases = $testtask->getLinkableCasesByStory(1, $task5, 't1.id < 19', array(2));
r(count($cases)) && p() && e(10); // 产品 1 测试单 5 查询 id < 19 并排除用例 2 后可关联的用例数为 10。
r($cases) && p('18:id,title,pri,type,auto,status,storyTitle') && e('18,这个是测试用例18,2,install,no,normal,软件需求2');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('16:id,title,pri,type,auto,status,storyTitle') && e('16,这个是测试用例16,4,performance,no,investigate,软件需求2'); // 查看可关联的用例 16 的详细信息。
r($cases) && p('14:id,title,pri,type,auto,status,storyTitle') && e('14,这个是测试用例14,2,other,no,normal,软件需求2');            // 查看可关联的用例 14 的详细信息。

$cases = $testtask->getLinkableCasesByStory(1, $task5, 't1.id < 19', array(), $pager);
r(count($cases)) && p() && e(5); // 产品 1 测试单 5 查询 id < 19 并限制每页查询 5 条后可关联的用例数为 5。
r($cases) && p('18:id,title,pri,type,auto,status,storyTitle') && e('18,这个是测试用例18,2,install,no,normal,软件需求2');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('16:id,title,pri,type,auto,status,storyTitle') && e('16,这个是测试用例16,4,performance,no,investigate,软件需求2'); // 查看可关联的用例 16 的详细信息。
r($cases) && p('14:id,title,pri,type,auto,status,storyTitle') && e('14,这个是测试用例14,2,other,no,normal,软件需求2');            // 查看可关联的用例 14 的详细信息。

$cases = $testtask->getLinkableCasesByStory(1, $task5, 't1.id < 19', array(2), $pager);
r(count($cases)) && p() && e(5); // 产品 1 测试单 5 查询 id < 19、排除用例 2 并限制每页查询 5 条后可关联的用例数为 5。
r($cases) && p('18:id,title,pri,type,auto,status,storyTitle') && e('18,这个是测试用例18,2,install,no,normal,软件需求2');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('16:id,title,pri,type,auto,status,storyTitle') && e('16,这个是测试用例16,4,performance,no,investigate,软件需求2'); // 查看可关联的用例 16 的详细信息。
r($cases) && p('14:id,title,pri,type,auto,status,storyTitle') && e('14,这个是测试用例14,2,other,no,normal,软件需求2');            // 查看可关联的用例 14 的详细信息。

$cases = $testtask->getLinkableCasesByStory(1, $task5, 't1.id < 19', array(2,3,4,5,6,7,8,9,10,11,12), $pager);
r(count($cases)) && p() && e(3); // 产品 1 测试单 5 查询 id < 19、排除用例 2-12 并限制每页查询 5 条后可关联的用例数为 3。
r($cases) && p('18:id,title,pri,type,auto,status,storyTitle') && e('18,这个是测试用例18,2,install,no,normal,软件需求2');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('16:id,title,pri,type,auto,status,storyTitle') && e('16,这个是测试用例16,4,performance,no,investigate,软件需求2'); // 查看可关联的用例 16 的详细信息。
r($cases) && p('14:id,title,pri,type,auto,status,storyTitle') && e('14,这个是测试用例14,2,other,no,normal,软件需求2');            // 查看可关联的用例 14 的详细信息。

$testtask->lang->navGroup->testtask = 'project';
$testtask->session->set('project', 2);
$cases = $testtask->getLinkableCasesByStory(1, $task5);
r(count($cases)) && p() && e(6); // 产品 1 测试单 5 在项目 2 中可关联的用例数为 6。
r($cases) && p('19:id,title,pri,type,auto,status,storyTitle') && e('19,这个是测试用例19,3,security,no,blocked,用户需求1');        // 查看可关联的用例 19 的详细信息。
r($cases) && p('18:id,title,pri,type,auto,status,storyTitle') && e('18,这个是测试用例18,2,install,no,normal,软件需求2');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('16:id,title,pri,type,auto,status,storyTitle') && e('16,这个是测试用例16,4,performance,no,investigate,软件需求2'); // 查看可关联的用例 16 的详细信息。
