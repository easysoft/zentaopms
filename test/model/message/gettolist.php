#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/message.class.php';
su('admin');

/**

title=测试 messageModel->getToList();
cid=1
pid=1

通过一条todo数据展示 >> admin
通过一条testtask数据展示 >> user3
通过一条meeting数据展示 >> admin,admin,
通过一条mr数据展示 >> admin,admin
通过一条release数据展示 >> po1
通过一条task数据展示 >> po82

*/

$message = new messageTest();

r($message->getToListTest('todo'))     && p() && e('admin');        //通过一条todo数据展示$toList
r($message->getToListTest('testtask')) && p() && e('user3');        //通过一条testtask数据展示$toList
r($message->getToListTest('meeting'))  && p() && e('admin,admin,'); //通过一条meeting数据展示$toList
r($message->getToListTest('mr'))       && p() && e('admin,admin');  //通过一条mr数据展示$toList
r($message->getToListTest('release'))  && p() && e('po1');          //通过一条release数据展示$toList
r($message->getToListTest('task'))     && p() && e('po82');         //通过一条task数据展示$toList