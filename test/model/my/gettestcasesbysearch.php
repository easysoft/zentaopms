#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/my.class.php';
su('admin');

/**

title=测试 myModel->getTestcasesBySearch();
cid=1
pid=1

获取testcase状态的项目 >> com.ngtesting.autotest.test.TestLogin账号过期,normal
获取testcase的统计 >> 20
获取testcase状态的项目 >> com.ngtesting.autotest.test.TestLogin密码错误,normal
获取testcase的统计 >> 18

*/

$my    = new myTest();
$type  = array('contribute', 'openedbyme');
$order = 'id_desc';

$cases1 = $my->getTestcasesBySearchTest(0, $type[0], $order);
$cases2 = $my->getTestcasesBySearchTest(0, $type[1], $order);

r($cases1)        && p('560:title,status') && e('com.ngtesting.autotest.test.TestLogin账号过期,normal');//获取testcase状态的项目
r(count($cases1)) && p()                   && e('20');                                                 //获取testcase的统计
r($cases2)        && p('439:title,status') && e('com.ngtesting.autotest.test.TestLogin密码错误,normal');//获取testcase状态的项目
r(count($cases2)) && p()                   && e('18');                                                 //获取testcase的统计