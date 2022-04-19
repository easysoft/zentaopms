#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->createFeedbackLink();
cid=1
pid=1

测试创建module 1821 的feedbacklink >> title='产品模块1'
测试创建module 1822 的feedbacklink >> title='产品模块2'
测试创建module 1981 的feedbacklink >> title='产品模块161'
测试创建module 1982 的feedbacklink >> title='产品模块162'
测试创建module 1621 的feedbacklink >> title='模块1601'
测试创建module 1622 的feedbacklink >> title='模块1602'
测试创建module 21 的feedbacklink >> title='模块1'
测试创建module 22 的feedbacklink >> title='模块2'

*/
$moduleID = array(1821, 1822, 1981, 1982, 1621, 1622, 21, 22);

$tree = new treeTest();

r($tree->createFeedbackLinkTest($moduleID[0])) && p() && e("title='产品模块1'");   // 测试创建module 1821 的feedbacklink
r($tree->createFeedbackLinkTest($moduleID[1])) && p() && e("title='产品模块2'");   // 测试创建module 1822 的feedbacklink
r($tree->createFeedbackLinkTest($moduleID[2])) && p() && e("title='产品模块161'"); // 测试创建module 1981 的feedbacklink
r($tree->createFeedbackLinkTest($moduleID[3])) && p() && e("title='产品模块162'"); // 测试创建module 1982 的feedbacklink
r($tree->createFeedbackLinkTest($moduleID[4])) && p() && e("title='模块1601'");    // 测试创建module 1621 的feedbacklink
r($tree->createFeedbackLinkTest($moduleID[5])) && p() && e("title='模块1602'");    // 测试创建module 1622 的feedbacklink
r($tree->createFeedbackLinkTest($moduleID[6])) && p() && e("title='模块1'");       // 测试创建module 21 的feedbacklink
r($tree->createFeedbackLinkTest($moduleID[7])) && p() && e("title='模块2'");       // 测试创建module 22 的feedbacklink