#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/company.class.php';
su('admin');

zdTable('company')->gen(1);
zdTable('user')->gen(100);

/**

title=测试companyModel->getCompanyUserPairsTest();
cid=1
pid=1

公司用户键值组合 >> 易软天创网络科技有限公司/admin
公司用户键值数量统计 >> 100

*/

$count = array('0','1');

$company = new companyTest();
r($company->getCompanyUserPairsTest($count[0])) && p('admin')  && e('易软天创网络科技有限公司/admin'); // 公司用户键值组合
r($company->getCompanyUserPairsTest($count[1])) && p()         && e('100');                               // 公司用户键值数量统计
