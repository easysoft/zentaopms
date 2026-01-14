#!/usr/bin/env php
<?php

/**

title=测试 userModel::mergeAclsToUserView();
timeout=0
cid=19649

- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'admin', $userView1, $acls1, '' 属性programs @1,2,3
- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'user1', $userView2, $acls2, '' 属性programs @100,200
- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'user2', $userView3, $acls3, '' 属性products @5,6,7
- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'user3', $userView4, $acls4, '' 属性sprints @8,9,,18
- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'user4', $userView5, $acls2, '3, 4' 属性projects @100,200,3,4
- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'user5', $userView6, $acls5, '' 属性programs @10,11,12
- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'user6', $userView7, $acls6, ''
 - 属性programs @15,16
 - 属性products @13,14

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('userview')->gen(5);
zenData('project')->loadYaml('project')->gen(20);
zenData('projectadmin')->gen(5);

global $app;
if(!isset($app->company)) $app->company = new stdClass();
$app->company->admins = ',admin,';

su('admin');

$userTest = new userModelTest();

// 准备测试数据:userView对象
$userView1 = new stdclass();
$userView1->account  = 'admin';
$userView1->programs = '1,2,3';
$userView1->products = '1,2,3';
$userView1->sprints  = '1,2,3';
$userView1->projects = '1,2,3';

$userView2 = new stdclass();
$userView2->account  = 'user1';
$userView2->programs = '1,2,3';
$userView2->products = '1,2,3';
$userView2->sprints  = '1,2,3';
$userView2->projects = '1,2,3';

$userView3 = new stdclass();
$userView3->account  = 'user2';
$userView3->programs = '4,5,6';
$userView3->products = '4,5,6';
$userView3->sprints  = '4,5,6';
$userView3->projects = '4,5,6';

$userView4 = new stdclass();
$userView4->account  = 'user3';
$userView4->programs = '7,8,9';
$userView4->products = '7,8,9';
$userView4->sprints  = '7,8,9';
$userView4->projects = '7,8,9';

$userView5 = new stdclass();
$userView5->account  = 'user4';
$userView5->programs = '1,2';
$userView5->products = '1,2';
$userView5->sprints  = '1,2';
$userView5->projects = '1,2';

$userView6 = new stdclass();
$userView6->account  = 'user5';
$userView6->programs = '10,11,12';
$userView6->products = '10,11,12';
$userView6->sprints  = '10,11,12';
$userView6->projects = '10,11,12';

$userView7 = new stdclass();
$userView7->account  = 'user6';
$userView7->programs = '13,14';
$userView7->products = '13,14';
$userView7->sprints  = '13,14';
$userView7->projects = '13,14';

// 准备测试数据:acls数组
$acls1 = array(
    'programs' => array(10, 20, 30),
    'products' => array(10, 20, 30),
    'sprints'  => array(10, 20, 30),
    'projects' => array(10, 20, 30),
);

$acls2 = array(
    'programs' => array(100, 200),
    'products' => array(100, 200),
    'sprints'  => array(100, 200),
    'projects' => array(100, 200),
);

$acls3 = array(
    'programs' => array(5, 6, 7),
    'products' => array(5, 6, 7),
    'sprints'  => array(5, 6, 7),
    'projects' => array(5, 6, 7),
);

$acls4 = array(
    'programs' => array(8, 9),
    'products' => array(8, 9),
    'sprints'  => array(8, 9),
    'projects' => array(8, 9),
);

$acls5 = array();

$acls6 = array(
    'programs' => array(15, 16),
    'products' => array(),
    'sprints'  => array(),
    'projects' => array(),
);

$acls7 = array(
    'programs' => array(),
    'products' => array(17, 18),
    'sprints'  => array(),
    'projects' => array(),
);

su('admin');
r($userTest->mergeAclsToUserViewTest('admin', $userView1, $acls1, '')) && p('programs', '|') && e('1,2,3');

su('user1');
r($userTest->mergeAclsToUserViewTest('user1', $userView2, $acls2, '')) && p('programs', '|') && e('100,200');

su('user2');
r($userTest->mergeAclsToUserViewTest('user2', $userView3, $acls3, '')) && p('products', '|') && e('5,6,7');

su('user3');
r($userTest->mergeAclsToUserViewTest('user3', $userView4, $acls4, '')) && p('sprints', '|') && e('8,9,,18');

su('user4');
r($userTest->mergeAclsToUserViewTest('user4', $userView5, $acls2, '3,4')) && p('projects', '|') && e('100,200,3,4');

su('user5');
r($userTest->mergeAclsToUserViewTest('user5', $userView6, $acls5, '')) && p('programs', '|') && e('10,11,12');

su('user6');
r($userTest->mergeAclsToUserViewTest('user6', $userView7, $acls6, '')) && p('programs|products', '|') && e('15,16|13,14');