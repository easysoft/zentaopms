#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('company')->gen(1);
zenData('user')->loadYaml('user')->gen(1000);
zenData('userquery')->loadYaml('userquery')->gen(3);
zenData('dept')->gen(10);

/**

title=测试companyModel->getUsersTest();
timeout=0
cid=15734

- 查询内部人员
 - 第0条的account属性 @admin
 - 第0条的realname属性 @admin
- 查询内部人员统计 @999
- 查询外部人员
 - 第0条的account属性 @outside1
 - 第0条的realname属性 @外用户1
- 查询外部部人员统计 @1
- 根据部门查询人员
 - 第0条的account属性 @admin
 - 第0条的realname属性 @admin
 - 第0条的dept属性 @1
- type未传值按部门查询人员 @999
- 根据部门查询人员统计 @10
- 根据查询条件查询
 - 第0条的account属性 @admin
 - 第0条的realname属性 @admin
- 根据查询条件'查询统计 @1
- 根据account降序排序
 - 第0条的account属性 @admin
 - 第0条的realname属性 @admin

*/

$count      = array('0','1');
$browseType = array('inside', 'outside');
$type       = array('', 'bydept');
$queryID    = array('0', '3');
$deptID     = array('0', '1');
$sort       = array('realname_asc', 'account_desc');

$company = new companyModelTest();
r($company->getUsersTest($count[0], $browseType[0], $type[0], $queryID[0], $deptID[0], $sort[0])) && p('0:account,realname')      && e('admin,admin');          // 查询内部人员
r($company->getUsersTest($count[1], $browseType[0], $type[0], $queryID[0], $deptID[0], $sort[0])) && p()                          && e('999');                  // 查询内部人员统计
r($company->getUsersTest($count[0], $browseType[1], $type[0], $queryID[0], $deptID[0], $sort[0])) && p('0:account,realname')      && e('outside1,外用户1');     // 查询外部人员
r($company->getUsersTest($count[1], $browseType[1], $type[0], $queryID[0], $deptID[0], $sort[0])) && p()                          && e('1');                    // 查询外部部人员统计
r($company->getUsersTest($count[0], $browseType[0], $type[1], $queryID[0], $deptID[1], $sort[0])) && p('0:account,realname,dept') && e('admin,admin,1');        // 根据部门查询人员
r($company->getUsersTest($count[1], $browseType[0], $type[0], $queryID[0], $deptID[1], $sort[0])) && p()                          && e('999');                  // type未传值按部门查询人员
r($company->getUsersTest($count[1], $browseType[0], $type[1], $queryID[0], $deptID[1], $sort[0])) && p()                          && e('10');                   // 根据部门查询人员统计
r($company->getUsersTest($count[0], $browseType[0], $type[0], $queryID[1], $deptID[0], $sort[0])) && p('0:account,realname')      && e('admin,admin');          // 根据查询条件查询
r($company->getUsersTest($count[1], $browseType[0], $type[0], $queryID[1], $deptID[0], $sort[0])) && p()                          && e('1');                    // 根据查询条件'查询统计
r($company->getUsersTest($count[0], $browseType[0], $type[0], $queryID[0], $deptID[0], $sort[1])) && p('0:account,realname')      && e('admin,admin');          // 根据account降序排序
