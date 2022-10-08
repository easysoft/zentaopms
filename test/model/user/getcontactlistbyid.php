#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel::getContactListByID();
cid=1
pid=1

获取ID为1的联系人列表名称 >> 联系人列表1
获取ID为60的联系人列表创建者 >> admin
获取ID为1000的联系人列表，返回空 >> 0
获取ID为false的联系人列表，返回空 >> 0
获取ID为null的联系人列表，返回空 >> 0

*/

$user = new userTest();

r($user->getContactListByIDTest(1))     && p('listName') && e('联系人列表1'); //获取ID为1的联系人列表名称
r($user->getContactListByIDTest(60))    && p('account') && e('admin');        //获取ID为60的联系人列表创建者
r($user->getContactListByIDTest(1000))  && p() && e('0');                     //获取ID为1000的联系人列表，返回空
r($user->getContactListByIDTest(false)) && p() && e('0');                     //获取ID为false的联系人列表，返回空
r($user->getContactListByIDTest(null))  && p() && e('0');                     //获取ID为null的联系人列表，返回空