#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('project')->config('program')->gen(1);
zdTable('user')->gen(51);
zdTable('userview')->config('userview')->gen(50);
zdTable('userquery')->config('userquery')->gen(1);

su('admin');

/**

title=测试 personnelModel->getAccessiblePersonnel();
cid=1
pid=1

*/

$personnel = new personnelTest('admin');

$programID  = array(1, 2);
$deptID     = array(0, 1, 2);
$browseType = array('all', 'bysearch');
$queryID    = array(0, 1);

$result1 = $personnel->getAccessiblePersonnelTest($programID[0], $deptID[0], $browseType[0], $queryID[0]);
$result2 = $personnel->getAccessiblePersonnelTest($programID[0], $deptID[1], $browseType[0], $queryID[0]);
$result3 = $personnel->getAccessiblePersonnelTest($programID[0], $deptID[2], $browseType[0], $queryID[0]);
$result4 = $personnel->getAccessiblePersonnelTest($programID[1], $deptID[0], $browseType[0], $queryID[0]);
global $tester;
$tester->session->set('accessibleQuery', "t2.account = 'user1'");
$result5 = $personnel->getAccessiblePersonnelTest($programID[0], $deptID[0], $browseType[1], $queryID[0]);
$result6 = $personnel->getAccessiblePersonnelTest($programID[0], $deptID[0], $browseType[1], $queryID[1]);

r(count($result1)) && p() && e(50);             //测试programID为1，部门ID为0，浏览类型为all，查询ID为0的结果集的数量
r($result1) && p('50:account') && e('user49');  //测试programID为1，部门ID为0，浏览类型为all，查询ID为0的结果集内id为50的account

r(count($result2)) && p() && e(9);              //测试programID为1，部门ID为1，浏览类型为all，查询ID为0的结果集的数量
r($result2) && p('9:account') && e('user8');    //测试programID为1，部门ID为1，浏览类型为all，查询ID为0的结果集内id为9的account

r(count($result3)) && p() && e(10);             //测试programID为1，部门ID为2，浏览类型为all，查询ID为0的结果集的数量
r($result3) && p('19:account') && e('user18');  //测试programID为1，部门ID为2，浏览类型为all，查询ID为0的结果集内id为19的account

r(count($result4)) && p() && e('0');            //测试programID为2，部门ID为0，浏览类型为all，查询ID为0的结果集的数量

r(count($result5)) && p() && e(1);              //测试programID为1，部门ID为0，浏览类型为bysearch，查询ID为0的结果集的数量
r($result5) && p('2:account') && e('user1');    //测试programID为1，部门ID为0，浏览类型为bysearch，查询ID为0的结果集内id为2的account

r(count($result6)) && p() && e(1);              //测试programID为1，部门ID为0，浏览类型为bysearch，查询ID为1的结果集的数量
r($result6) && p('11:account') && e('user10');    //测试programID为1，部门ID为0，浏览类型为bysearch，查询ID为1的结果集内id为2的account
