#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试companyModel->update();
cid=15735
pid=1

修改公司名称 >> 禅道软件
修改公司电话 >> 18640561601
修改公司地址 >> 青岛市黄岛区
修改公司邮编 >> 266000
修改公司网址 >> www.chandao.com
修改公司网址前缀 >> https://
修改公司游客访问控制 >> 1

*/

$companyID = 1;

$changeName     = array('name' => '禅道软件');
$changePhone    = array('phone' => '18640561601');
$changeAddress  = array('address' => '青岛市黄岛区');
$changeZipcode  = array('zipcode' =>'266000');
$changeWebsite  = array('website' => 'www.chandao.com');
$changeBackyard = array('backyard' => 'https://');
$changeGuest    = array('guest' => '1');

$company = new companyModelTest();
r($company->updateTest($companyID, $changeName))     && p('name')     && e('禅道软件');        // 修改公司名称
r($company->updateTest($companyID, $changePhone))    && p('phone')    && e('18640561601');     // 修改公司电话
r($company->updateTest($companyID, $changeAddress))  && p('address')  && e('青岛市黄岛区');    // 修改公司地址
r($company->updateTest($companyID, $changeZipcode))  && p('zipcode')  && e('266000');          // 修改公司邮编
r($company->updateTest($companyID, $changeWebsite))  && p('website')  && e('www.chandao.com'); // 修改公司网址
r($company->updateTest($companyID, $changeBackyard)) && p('backyard') && e('https://');        // 修改公司网址前缀
r($company->updateTest($companyID, $changeGuest))    && p('guest')    && e('1');               // 修改公司游客访问控制

