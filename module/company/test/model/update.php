#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试companyModel->update();
cid=15735
pid=1

修改公司名称 >> 修改公司名一
修改公司电话 >> 18640561601
修改公司地址 >> 地址信息
修改公司邮编 >> 710000
修改公司网址 >> www.baidu.com
修改公司网址前缀 >> https://
修改公司游客访问控制 >> 1

*/

$companyID = 1;

$changeName     = array('name' => '修改公司名一');
$changePhone    = array('phone' => '18640561601');
$changeAddress  = array('address' => '地址信息');
$changeZipcode  = array('zipcode' =>'710000');
$changeWebsite  = array('website' => 'www.baidu.com');
$changeBackyard = array('backyard' => 'https://');
$changeGuest    = array('guest' => '1');

$company = new companyModelTest();
r($company->updateObject($companyID, $changeName))     && p('name')     && e('修改公司名一');  // 修改公司名称
r($company->updateObject($companyID, $changePhone))    && p('phone')    && e('18640561601');   // 修改公司电话
r($company->updateObject($companyID, $changeAddress))  && p('address')  && e('地址信息');      // 修改公司地址
r($company->updateObject($companyID, $changeZipcode))  && p('zipcode')  && e('710000');        // 修改公司邮编
r($company->updateObject($companyID, $changeWebsite))  && p('website')  && e('www.baidu.com'); // 修改公司网址
r($company->updateObject($companyID, $changeBackyard)) && p('backyard') && e('https://');      // 修改公司网址前缀
r($company->updateObject($companyID, $changeGuest))    && p('guest')    && e('1');             // 修改公司游客访问控制

