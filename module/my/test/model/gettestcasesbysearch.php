#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('case')->loadYaml('case_admin_create')->gen('20');
zenData('testrun')->gen('20');
zenData('user')->gen('1');

su('admin');

/**

title=测试 myModel->getTestcasesBySearch();
timeout=0
cid=17304

- 测试通过搜索获取用例 1 的当前用例名称和状态
 - 属性title @这个是测试用例19
 - 属性status @blocked
- 测试通过搜索获取用例 1 的用例数量 @10
- 测试通过搜索获取用例 2 的当前用例名称和状态
 - 属性title @这个是测试用例1
 - 属性status @wait
- 测试通过搜索获取用例 2 的用例数量 @1

*/

$my    = new myModelTest();
$type  = array('contribute', 'openedbyme');
$order = 'id_desc';

global $tester;
$tester->session->set('workTestcaseQuery', "title like '%测试%'");
$tester->session->set('contributeTestcaseQuery', "title like '%测试%'");

$cases1 = $my->getTestcasesBySearchTest(0, $type[0], $order);
$cases2 = $my->getTestcasesBySearchTest(0, $type[1], $order);

r(current($cases1)) && p('title,status') && e('这个是测试用例19,blocked'); // 测试通过搜索获取用例 1 的当前用例名称和状态
r(count($cases1))   && p()               && e('10');                       // 测试通过搜索获取用例 1 的用例数量
r(current($cases2)) && p('title,status') && e('这个是测试用例1,wait');     // 测试通过搜索获取用例 2 的当前用例名称和状态
r(count($cases2))   && p()               && e('1');                        // 测试通过搜索获取用例 2 的用例数量