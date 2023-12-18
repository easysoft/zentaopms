#!/usr/bin/env php
<?php
/**
title=测试 userModel->computePasswordStrength();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$userTest = new userTest();

r($userTest->computePasswordStrengthTest(''))             && p()  && e(0); // 密码为空，强度为弱。

/* 测试密码包含纯数字时不同长度的密码强度。*/
r($userTest->computePasswordStrengthTest('12345'))        && p()  && e(0); // 密码包含纯数字，长度小于 6，强度为弱。
r($userTest->computePasswordStrengthTest('123456'))       && p()  && e(0); // 密码包含纯数字，长度大于等于 6，强度为弱。
r($userTest->computePasswordStrengthTest('1234567890'))   && p()  && e(0); // 密码包含纯数字，长度大于等于 10，强度为弱。

/* 测试密码包含纯大写字母时不同长度的密码强度。*/
r($userTest->computePasswordStrengthTest('ADMIN'))        && p()  && e(0); // 密码包含纯大写字母，长度小于 6，强度为弱。
r($userTest->computePasswordStrengthTest('ADMINS'))       && p()  && e(0); // 密码包含纯大写字母，长度大于等于 6，强度为弱。
r($userTest->computePasswordStrengthTest('ADMINSTRATOR')) && p()  && e(0); // 密码包含纯大写字母，长度大于等于 10，强度为弱。

/* 测试密码包含纯小写字母时不同长度的密码强度。*/
r($userTest->computePasswordStrengthTest('admin'))        && p()  && e(0); // 密码包含纯小写字母，长度小于 6，强度为弱。
r($userTest->computePasswordStrengthTest('admins'))       && p()  && e(0); // 密码包含纯小写字母，长度大于等于 6，强度为弱。
r($userTest->computePasswordStrengthTest('adminstrator')) && p()  && e(0); // 密码包含纯小写字母，长度大于等于 10，强度为弱。

/* 测试密码包含纯特殊字符时不同长度的密码强度。*/
r($userTest->computePasswordStrengthTest('@@@@@'))        && p()  && e(0); // 密码包含纯特殊字符，长度小于 6，强度为弱。
r($userTest->computePasswordStrengthTest('@@@@@!'))       && p()  && e(0); // 密码包含纯特殊字符，长度大于等于 6，强度为弱。
r($userTest->computePasswordStrengthTest('@@@@@!!!!!'))   && p()  && e(0); // 密码包含纯特殊字符，长度大于等于 10，强度为弱。

/* 测试密码包含小写字母和数字时不同长度的密码强度。*/
r($userTest->computePasswordStrengthTest('adm12'))        && p()  && e(0); // 密码包含小写字母和数字，长度小于 6，强度为弱。
r($userTest->computePasswordStrengthTest('adm123'))       && p()  && e(0); // 密码包含小写字母和数字，长度大于等于 6，强度为弱。
r($userTest->computePasswordStrengthTest('admin12345'))   && p()  && e(0); // 密码包含小写字母和数字，长度大于等于 10，强度为弱。

/* 测试密码包含大写字母和数字时不同长度的密码强度。*/
r($userTest->computePasswordStrengthTest('ADM12'))        && p()  && e(0); // 密码包含大写字母和数字，长度小于 6，强度为弱。
r($userTest->computePasswordStrengthTest('ADM123'))       && p()  && e(0); // 密码包含大写字母和数字，长度大于等于 6，强度为弱。
r($userTest->computePasswordStrengthTest('ADMIN12345'))   && p()  && e(0); // 密码包含大写字母和数字，长度大于等于 10，强度为弱。

/* 测试密码包含大小写字母时不同长度的密码强度。*/
r($userTest->computePasswordStrengthTest('Admin'))        && p()  && e(0); // 密码包含大小写字母，长度小于 6，强度为弱。
r($userTest->computePasswordStrengthTest('Admins'))       && p()  && e(0); // 密码包含大小写字母，长度大于等于 6，强度为弱。
r($userTest->computePasswordStrengthTest('Adminstrator')) && p()  && e(0); // 密码包含大小写字母，长度大于等于 10，强度为弱。

/* 测试密码包含大小写字母和数字时不同长度的密码强度。*/
r($userTest->computePasswordStrengthTest('Adm12'))        && p()  && e(0); // 密码包含大小写字母和数字，长度小于 6，强度为弱。
r($userTest->computePasswordStrengthTest('Admin123'))     && p()  && e(1); // 密码包含大小写字母和数字，长度大于等于 6，强度为中。
r($userTest->computePasswordStrengthTest('Admin12345'))   && p()  && e(1); // 密码包含大小写字母和数字，长度大于等于 10，强度为中。

/* 测试密码包含大小写字母和特殊字符时不同长度的密码强度。*/
r($userTest->computePasswordStrengthTest('@Ad1'))         && p()  && e(0); // 密码包含大小写字母、数字和特殊字符，长度小于 6，强度为弱。
r($userTest->computePasswordStrengthTest('@Admin123'))    && p()  && e(1); // 密码包含大小写字母、数字和特殊字符，长度大于等于 6，强度为中。
r($userTest->computePasswordStrengthTest('@Admin123@'))   && p()  && e(2); // 密码包含大小写字母、数字和特殊字符，长度大于等于 10，强度为强。
