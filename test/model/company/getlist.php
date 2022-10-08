#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/company.class.php';
su('admin');

/**

title=测试companyModel->getListTest();
cid=1
pid=1

公司信息列表查询 >> 易软天创网络科技有限公司,,admin,
公司信息统计 >> 2

*/

$count = array('0','1');

$company = new companyTest();
r($company->getListTest($count[0])) && p('0:name,admins')  && e('易软天创网络科技有限公司,,admin,'); // 公司信息列表查询
r($company->getListTest($count[1])) && p()                 && e('2');                                // 公司信息统计