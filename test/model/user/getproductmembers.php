#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->getProductMembers();
cid=1
pid=1

获取ID为1的产品的团队成员，判断是否包含pm92 >> pm92
获取ID为4的产品的团队成员，判断是否包含user5 >> user5
获取ID为2的产品的干系人，判断是否包含po3 >> po3
获取ID为3的产品的干系人，判断是否包含user14 >> user14

*/

$user = new userTest();
$products = array(1 => 1, 2 => 2, 3 => 3, 4 => 4);

$members = $user->getProductMembersTest($products);
$teamGroups        = $members[0];
$stakeholderGroups = $members[0];

r($teamGroups)         && p('1:pm92')   && e('pm92');   //获取ID为1的产品的团队成员，判断是否包含pm92
r($teamGroups)         && p('4:user5')  && e('user5');  //获取ID为4的产品的团队成员，判断是否包含user5
r($stakeholderGroups)  && p('2:po3')    && e('po3');    //获取ID为2的产品的干系人，判断是否包含po3
r($stakeholderGroups)  && p('3:user14') && e('user14'); //获取ID为3的产品的干系人，判断是否包含user14

