#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';
su('admin');

zenData('dept')->loadYaml('dept')->gen(30);
zenData('user')->gen(200);

/**

title=测试 deptModel->getUsers();
timeout=0
cid=15978

- 全部用户查询
 - 第101条的account属性 @test2
 - 第101条的dept属性 @11
 - 第101条的realname属性 @测试2
- 外部用户查询 @0
- 根据部门查询用户
 - 第9条的account属性 @user19
 - 第9条的dept属性 @2
 - 第9条的realname属性 @用户19
- 根据account排序
 - 第0条的account属性 @user40
 - 第0条的dept属性 @5
 - 第0条的realname属性 @用户40
- 全部用户查询统计 @200
- 外部用户查询统计 @0
- 根据部门查询用户统计 @10

*/

$deptIDList = array('0', '2', '5');
$browseType = array('inside', 'outside');
$count      = array('0', '1');
$orderBy    = array('id', 'account');

$dept = new deptTest();
r($dept->getUsersTest($browseType[0], array(), $count[0], $orderBy[0])) && p('101:account,dept,realname')               && e('test2,11,测试2');   //全部用户查询
r($dept->getUsersTest($browseType[1], array(), $count[0], $orderBy[0])) && p('')                                        && e('0');                //外部用户查询
r($dept->getUsersTest($browseType[0], array($deptIDList[1]), $count[0], $orderBy[0])) && p('9:account,dept,realname')   && e('user19,2,用户19');  //根据部门查询用户
r($dept->getUsersTest($browseType[0], array($deptIDList[2]), $count[0], $orderBy[1])) && p('0:account,dept,realname')   && e('user40,5,用户40');  //根据account排序
r($dept->getUsersTest($browseType[0], array(), $count[1], $orderBy[0])) && p()                                          && e('200');              //全部用户查询统计
r($dept->getUsersTest($browseType[1], array(), $count[1], $orderBy[0])) && p()                                          && e('0');                //外部用户查询统计
r($dept->getUsersTest($browseType[0], array($deptIDList[1]), $count[1], $orderBy[0])) && p()                            && e('10');               //根据部门查询用户统计
