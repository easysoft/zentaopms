#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/company.class.php';
su('admin');

/**

title=测试companyModel->getCompanyUserPairsTest();
cid=1
pid=1

公司用户键值组合 >> 易软天创网络科技有限公司/研发主管1
公司用户键值数量统计 >> 1000

*/

$count = array('0','1');

$company = new companyTest();
r($company->getCompanyUserPairsTest($count[0])) && p('td1')  && e('易软天创网络科技有限公司/研发主管1'); // 公司用户键值组合
r($company->getCompanyUserPairsTest($count[1])) && p()       && e('1000');                               // 公司用户键值数量统计