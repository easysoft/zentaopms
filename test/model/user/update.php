#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';

zdTable('user')->gen(10);

su('admin');

/**

title=测试 userModel->updateUser();
cid=1
pid=1

编辑用户，返回新的用户名 >> newtestuser4
编辑用户，有重名用户的情况 >> 『用户名』已经有『admin』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。
两次密码不相同的情况 >> 两次密码应该相同。<br/>
用户名包含特殊字符的情况 >> 『用户名』只能是字母、数字或下划线的组合三位以上。

*/

$user = new userTest();
$normalUser = array();
$normalUser['account']          = 'newtestuser4';
$normalUser['realname']         = '新的测试用户';
$normalUser['password1']        = 'a0933c1218a4e745bacdcf572b10eba7';
$normalUser['password2']        = 'a0933c1218a4e745bacdcf572b10eba7';
$normalUser['type']             = 'inside';
$normalUser['passwordStrength'] = 1;
$normalUser['visions']          = 'rnd';

$existUser = $normalUser;
$existUser['account'] = 'admin';

$differentPassword = $normalUser;
$differentPassword['password2'] = 'asfjsdklj1234jkljsdklfj19';

$specialUser = $normalUser;
$specialUser['account'] = '!@#Asdsd中文';

r($user->updateUserTest(3, $normalUser))         && p('account')     && e('newtestuser4'); //编辑用户，返回新的用户名
r($user->updateUserTest(3, $existUser))          && p('account:0')   && e('『用户名』已经有『admin』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。');//编辑用户，有重名用户的情况
r($user->updateUserTest(3, $differentPassword))  && p('password1:0') && e('两次密码应该相同。<br/>'); //两次密码不相同的情况
r($user->updateUserTest(3, $specialUser))        && p('account:0')   && e('『用户名』只能是字母、数字或下划线的组合三位以上。'); //用户名包含特殊字符的情况
