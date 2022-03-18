#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/company.class.php';
su('admin');

/**

title=测试companyModel->getUsersTest();
cid=1
pid=1

查询内部人员 >> admin,admin
查询内部人员统计 >> 999
查询外部人员 >> outside1,用户1
查询外部部人员统计 >> 1
根据部门查询人员 >> admin,admin,1
type未传值按部门查询人员 >> 999
根据部门查询人员统计 >> 10
根据查询条件查询 >> program1whitelist,项目集1白名单用户
根据查询条件'查询统计 >> 1
根据account降序排序 >> program1whitelist,项目集1白名单用户

*/

$count      = array('0','1');
$browseType = array('inside', 'outside');
$type       = array('', 'bydept');
$queryID    = array('0', '3');
$deptID     = array('0', '1');
$sort       = array('realname_asc', 'account_desc');

$company = new companyTest();
r($company->getUsersTest($count[0], $browseType[0], $type[0], $queryID[0], $deptID[0], $sort[0])) && p('0:account,realname')      && e('admin,admin');                         // 查询内部人员
r($company->getUsersTest($count[1], $browseType[0], $type[0], $queryID[0], $deptID[0], $sort[0])) && p()                          && e('999');                                 // 查询内部人员统计
r($company->getUsersTest($count[0], $browseType[1], $type[0], $queryID[0], $deptID[0], $sort[0])) && p('0:account,realname')      && e('outside1,用户1');                      // 查询外部人员
r($company->getUsersTest($count[1], $browseType[1], $type[0], $queryID[0], $deptID[0], $sort[0])) && p()                          && e('1');                                   // 查询外部部人员统计
r($company->getUsersTest($count[0], $browseType[0], $type[1], $queryID[0], $deptID[1], $sort[0])) && p('0:account,realname,dept') && e('admin,admin,1');                       // 根据部门查询人员
r($company->getUsersTest($count[1], $browseType[0], $type[0], $queryID[0], $deptID[1], $sort[0])) && p()                          && e('999');                                 // type未传值按部门查询人员
r($company->getUsersTest($count[1], $browseType[0], $type[1], $queryID[0], $deptID[1], $sort[0])) && p()                          && e('10');                                  // 根据部门查询人员统计
r($company->getUsersTest($count[0], $browseType[0], $type[0], $queryID[1], $deptID[0], $sort[0])) && p('0:account,realname')      && e('program1whitelist,项目集1白名单用户'); // 根据查询条件查询
r($company->getUsersTest($count[1], $browseType[0], $type[0], $queryID[1], $deptID[0], $sort[0])) && p()                          && e('1');                                   // 根据查询条件'查询统计
r($company->getUsersTest($count[0], $browseType[0], $type[0], $queryID[0], $deptID[0], $sort[1])) && p('0:account,realname')      && e('program1whitelist,项目集1白名单用户'); // 根据account降序排序