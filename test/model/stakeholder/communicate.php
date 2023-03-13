#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stakeholder.class.php';
su('admin');

/**

title=测试 stakeholderModel->communicate();
cid=1
pid=1

 >> 沟通内容
 >> 『对象ID』应当是数字。

*/
$userIDList = array('10', '', '17');
$comment    = array('comment' => '沟通内容');

$stakeholder = new stakeholderTest();
r($stakeholder->communicateTest($userIDList[0], $comment)) && p('0:comment')  && e('沟通内容');
r($stakeholder->communicateTest($userIDList[2]))           && p('0:comment')  && e('');
r($stakeholder->communicateTest($userIDList[1], $comment)) && p('objectID:0') && e('『对象ID』应当是数字。');

